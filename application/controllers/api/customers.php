<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Customers extends REST_Controller
{

    //private $_loggedIn = 0;
    private $_userSN = 0;

    function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->load->model('Admin_Model');
        $this->_loggedIn = $this->session->userdata('logged_hitech_admin_id');
        $this->_ParentLoggedIn = $this->session->userdata('logged_hitech_adminParentSN');
        if ($this->_ParentLoggedIn == 0) {
            $this->_userSN = $this->_loggedIn;
        } else {
            $this->_userSN = $this->_ParentLoggedIn;
        }
    }

    function list_get()
    {
        $sn = $this->get('sn');
        $dataArr = array('table_name' => 'customers');
        $get_save_search = $this->Admin_Model->getCommonTable($dataArr, 'save_search');

        if (!empty($get_save_search)) {
            $data_search = array();

            for ($i = 0; $i < count($get_save_search); $i++) {
                $dataArr = array('sn' => $get_save_search[$i]['selected_id']);
                $data = $this->Admin_Model->getCommonTable($dataArr, 'customers');
                array_push($data_search, $data[0]);
            }
            $this->response($data_search, 200); // 200 being the HTTP response code
        } else {
            if (!empty($sn)) {
                $dataArr = array('sn' => $sn);
                $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'customers');
            } else {
                $dataArr = array('C', 'O', $this->_loggedIn);
                $resultArr = $this->Admin_Model->getCustomersListByOmitRecords($dataArr);
                $response = array();
                foreach ($resultArr as $r) {
                    $emails = $this->Admin_Model->getCommonTable(array('customerSN' => $r['sn']), 'customers_email');
                    $r['email_attachment'] = array();
                    if (false != $emails) {
                        foreach ($emails as $e) {
                            $r['email_attachment'][] = $e['emailAddress'];
                        }
                    }
                    $r['email_attachment'] = implode("\n", $r['email_attachment']);
                    $response[] = $r;
                }
                $resultArr = $response;

                end($resultArr);
                $last_key     = key($resultArr);
                $last_value   = array_pop($resultArr);
                $resultArr          = array_merge(array($last_key => $last_value), $resultArr);
            }

            if ($resultArr) {
                $this->response($resultArr, 200); // 200 being the HTTP response code
            } else {
                $this->response(array('error' => 'Couldn\'t find any customers!'), 404);
            }
        }

    }

    function showOrHide_list_get(){
        $dataArr = array('table_name' => 'customers','userSN'=>$this->_loggedIn);
        $get_column_show_hide = $this->Admin_Model->getCommonTable($dataArr, 'column_show_hide');
        $this->response($get_column_show_hide, 200);
    }

    function searchSave_post()
    {
        $total_data = $this->post('data');
        $table_name = $this->post('name');
        $data = array();
        for ($i = 0; $i < count($total_data); $i++) {
            if ($table_name=="customers") {
                $data['selected_id'] = $total_data[$i]['sn'];
            } else {
                $data['selected_id'] = $total_data[$i]['id'];
            }            
            $data['table_name'] = $table_name;
            $data['userSN'] = $this->_loggedIn;
            $this->db->insert('save_search', $data);
        }
        $this->response(array('Success'), 200);
    }

    function columnChange_post()
    {
        $total_data = $this->post('data');
        $table_name = $this->post('name');
        $data = array();

        $this->db->where('table_name','customers')->where('userSN',$this->_loggedIn)->delete('column_show_hide');
        foreach ($total_data as $key => $value) {            
            if ((bool)$value['data'] == false) {
                $data['columns'] = $value['field'];
                $data['table_name'] = "customers";
                $data['userSN'] = $this->_loggedIn;
                $this->db->insert('column_show_hide', $data);
            }
        }        
        $this->response(array('Success'), 200);
    }

    function email_get()
    {
        $customerSN = $this->get('sn');
        if (!empty($customerSN)) {
            $dataArr = array('customerSN' => $customerSN);
            $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'customers_email');
        } else {
            $dataArr = array('C', 'O', $this->_loggedIn);
            $resultArr = $this->Admin_Model->getCustomersListByOmitRecords($dataArr);
        }
        $this->response($resultArr, 200);
    }

    function attachment_get()
    {
        $customerSN = $this->get('sn');
        if (!empty($customerSN)) {
            $dataArr = array('customerSN' => $customerSN);
            $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'customers_attachment');
        } else {
            $dataArr = array('C', 'O', $this->_loggedIn);
            $resultArr = $this->Admin_Model->getCustomersListByOmitRecords($dataArr);
        }
        $this->response($resultArr, 200);
    }

    function data_post()
    {
        if ($this->post('action') == "General") {
            $customerArr = $this->post('customer');
            $mode = $this->post('mode');
            $customer_email = $customerArr['email'];
            $customersemailArr = $this->post('customersemail');
            $duplicate_email = false;
            $duplicate_email_data = '';

            if ($mode == "edit") {
                $sn = $customerArr['sn'];
            } else {
                $sn = '';
            }
            if (!empty($customer_email)) {
                $mail_email = false;
                $attachment_email = false;
                if (empty($sn)) {
                    $duplicate_email_data = $this->db->where('email', $customer_email)->get('customers')->result_array();
                    $mail_email = true;
                } else {
                    $duplicate_email_data = $this->db->where('email', $customer_email)->where('sn != ', $sn)->get('customers')->result_array();
                    $attachment_email = true;
                }
                
                if (empty($duplicate_email_data)) {
                    $duplicate_email_data = $this->db->where('emailAddress',$customer_email)->get('customers_email')->result_array();
                } else {
                    $duplicate_email_data['id'] = $duplicate_email_data[0]['sn'];
                }

                if (empty($duplicate_email_data)) {
                    for ($i=0; $i < count($customersemailArr); $i++) { 
                        if (empty($sn)) {
                            $duplicate_email_data = $this->db->where('emailAddress',$customersemailArr[$i]['emailAddress'])->get('customers_email')->result_array();
                        } else {
                            $duplicate_email_data = $this->db->where('emailAddress',$customersemailArr[$i]['emailAddress'])->where('customerSN != ', $sn)->get('customers_email')->result_array();
                        }
                        if (empty($duplicate_email_data)) {
                            $duplicate_email_data = $this->db->where('email', $customersemailArr[$i]['emailAddress'])->get('customers')->result_array();
                            if (!empty($duplicate_email_data)) {
                                $duplicate_email_data['id'] = $duplicate_email_data[0]['sn'];
                                break;
                            }
                        } else {
                            $duplicate_email_data['id'] = $duplicate_email_data[0]['customerSN'];
                            break;
                        }
                    }
                } else {
                    if($mail_email==true){
                        $duplicate_email_data['id'] = $duplicate_email_data[0]['sn'];
                    }
                    if($attachment_email==true){
                        $duplicate_email_data['id'] = $duplicate_email_data[0]['customerSN'];
                    }
                }

                if (!empty($duplicate_email_data)) {
                    $duplicate_email = true;
                }                
            }

            if ($duplicate_email == false) {
                $dataArr = $customerArr;
                $dataArr['mailingList'] = (!empty($customerArr['mailingList']) ? "Y" : "N");
                $dataArr['anAccount'] = (!empty($customerArr['anAccount']) ? "Y" : "N");
                $dataArr['feedbackEnabled'] = (!empty($customerArr['feedbackEnabled']) ? "Y" : "N");
                $dataArr['useShippingAddress'] = (!empty($customerArr['useShippingAddress']) ? "Y" : "N");
                if ($sn > 0) {
                    $dataArr['mdUserSN'] = $this->_loggedIn;
                    $dataArr['modifiedDate'] = date('Y-m-d H:i:s');
                } else {
                    $dataArr['cdUserSN'] = $this->_loggedIn;
                    $dataArr['createdDate'] = date('Y-m-d H:i:s');
                }

                $resultArr = $this->Admin_Model->saveCommonTable($dataArr, $sn, 'customers');
                if ($sn > 0) {
                    
                    foreach ($customersemailArr as $customeremail) {
                        if (!empty($customeremail['emailAddress'])) {
                            if (array_key_exists('remove', $customeremail) && $customeremail['sn'] > 0) {
                                //echo 'hi';
                                $dataArr = array('sn' => $customeremail['sn']);
                                $this->Admin_Model->deleteCommonTable($dataArr, 'customers_email');
                            } else {
                                unset($customeremail['deleteme']);
                                $dataArr = $customeremail;
                                $dataArr['customerSN'] = $sn;
                                if ($dataArr['sn'] > 0) {
                                    $dataArr['mdUserSN'] = $this->_loggedIn;
                                    $dataArr['modifiedDate'] = date('Y-m-d H:i:s');
                                } else {
                                    $dataArr['cdUserSN'] = $this->_loggedIn;
                                    $dataArr['createdDate'] = date('Y-m-d H:i:s');
                                }
                                $this->Admin_Model->saveCommonTable($dataArr, $dataArr['sn'], 'customers_email');
                            }
                        }
                    }

                    $customersattachmentArr = $this->post('customersattachment');
                    foreach ($customersattachmentArr as $customersattachment) {

                        if (array_key_exists('remove', $customersattachment) && $customersattachment['sn'] > 0) {
                            //echo 'hi';
                            $dataArr = array('sn' => $customersattachment['sn']);
                            $this->Admin_Model->deleteCommonTable($dataArr, 'customers_attachment');
                        } else {
                            $dataArr = $customersattachment;
                            unset($dataArr['link']);
                            unset($dataArr['attachment']);
                            $dataArr['customerSN'] = $sn;
                            if ($dataArr['sn'] > 0) {
                                $dataArr['mdUserSN'] = $this->_loggedIn;
                                $dataArr['modifiedDate'] = date('Y-m-d H:i:s');
                            } else {
                                $dataArr['cdUserSN'] = $this->_loggedIn;
                                $dataArr['createdDate'] = date('Y-m-d H:i:s');
                            }
                            $this->Admin_Model->saveCommonTable($dataArr, $dataArr['sn'], 'customers_attachment');
                        }
                    }
                }

                $this->response(array('Success'), 200); // 200 being the HTTP response code
            } else {
                $this->response(array('message' => 'duplicate_email', 'data' => $duplicate_email_data), 200);
            }

        }
        if ($this->post('action') == 'attachment') {
            $attachment = $_FILES['file'];
            $attachmentSN = $this->post('sn');
            $customerSN = $this->post('customerSN');
            // var_dump("fine");
            // var_dump($attachment); die();
            $filename = '';
            if (!empty($_FILES['file']['name'])) {
                //UPload
                $uploadPath = $this->config->item('customers_upload_path');
                $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'file', "500", "5000", "5000");

                if (isset($return['error'])) {
                    return $this->response(array($return['error']), 200);
                }

                $filename = $return['upload_data']['file_name'];
            }
            if (!empty($filename)) {
                $dataArr = array('file' => $filename, 'customerSN' => $customerSN);
                if ($this->input->post('sn') > 0) {
                    $dataArr['modifiedDate'] = date('Y-m-d H:i:s');
                    if ($this->_ParentLoggedIn == 0) {
                        $dataArr['mdUserSN'] = $this->_loggedIn;
                    } else {
                        $dataArr['mdUserSN'] = $this->_loggedIn;
                    }
                    if ($this->input->post('description', true) != false) {
                        $dataArr['description'] = $this->input->post('description', true);
                    }
                    $this->Admin_Model->updateCommonTable($dataArr, $this->input->post('sn'), 'customers_attachment');
                    //redirect('/admin/customers/edit/'.$sn);
                } else {
                    $dataArr['createdDate'] = date('Y-m-d H:i:s');
                    if ($this->_ParentLoggedIn == 0) {
                        $dataArr['cdUserSN'] = $this->_loggedIn;
                    } else {
                        $dataArr['cdUserSN'] = $this->_loggedIn;
                    }
                    if ($this->input->post('description', true) != false) {
                        $dataArr['description'] = $this->input->post('description', true);
                    }
                    $this->Admin_Model->insertCommonTable_GetSN($dataArr, 0, 'customers_attachment');
                }
            }
            $this->response(array('Success'), 200); // 200 being the HTTP response code
        }
    }

    public function remove_post()
    {
        if ($this->Admin_Model->deleteCustomers($this->post('lists'))) {
            $this->response(array('message' => "Clients deleted.", 'status' => 'success'));
        } else {
            $this->response(array('message' => "Clients not deleted.", 'status' => 'error'));
        }
    }

    public function deleteCustomerMulti_post()
    {
        $id = $this->post('id');

        $ids = implode(',', $id);

        if ($this->db->query("delete from customers where sn in ($ids)")) {
            echo json_encode(array('response' => 'Selected customers were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected customers could not be deleted.', 'status' => 'error'));
        }
    }

    public function attachmentDelete_post()
    {
        $sn = $this->post('sn');
        if ($this->Admin_Model->deleteAttachment($sn)) {
            $this->response(array('message' => "Attachment deleted.", 'status' => 'success'));
        } else {
            $this->response(array('message' => "Attachment not deleted.", 'status' => 'error'));
        }
    }

    public function attachmentDescUpdate_post()
    {
        $sn = intval($this->post('sn'));
        $desc = FILTER_VAR($this->post('desc'), FILTER_SANITIZE_STRING);
        if ($this->Admin_Model->updateDescription($sn, $desc)) {
            $this->response(array('message' => "Description updated.", 'status' => 'success'));
        } else {
            $this->response(array('message' => "Description could not be updated.", 'status' => 'error'));
        }
    }

    public function deleteEmail_post()
    {
        $sn = intval($this->post('sn'));
        if ($this->Admin_Model->deleteEmail($sn)) {
            $this->response(array('message' => "Email deleted.", 'status' => 'success'));
        } else {
            $this->response(array('message' => "Email could not be deleted.", 'status' => 'error'));
        }
    }

    public function warehouse_get()
    {
        $data = $this->db->get_where('warehouse', array('adminSN' => $this->_userSN))->result_array();

        if (false == (bool)$data) {
            echo json_encode(array());
        } else {
            echo json_encode($data);
        }
    }

    public function warehouse_post()
    {
        $data = $this->post('models');
        $id = $data[0]['sn'];

        if ($this->post('action') == 'add' || $id == 0) {
            $insert = array(
                'name' => $data[0]['name'],
                'description' => $data[0]['description'],
                'status' => $data[0]['status'],
                'adminSN' => $this->_loggedIn,
                'cdUserSN' => $this->_loggedIn,
                'createdDate' => date('Y-m-d H:i:s')
            );
            if ($this->db->insert('warehouse', $insert)) {
                $insert['sn'] = $this->db->insert_id();
                echo json_encode($insert);
                //echo json_encode(array('response'=>'New warehouse was added.','status'=>'success'));
            } else {
                echo json_encode(array('response' => 'New warehouse could not be added.', 'status' => 'error'));
            }
        } elseif ($this->post('action') == 'update' || $id > 0) {
            $update = array(
                'sn' => $data[0]['sn'],
                'name' => $data[0]['name'],
                'description' => $data[0]['description'],
                'status' => $data[0]['status'],
                'mdUserSN' => $this->_loggedIn,
                'modifiedDate' => date('Y-m-d H:i:s')
            );
            $this->db->where('sn', intval($id));
            if ($this->db->update('warehouse', $update)) {

                echo json_encode($update);
                //echo json_encode(array('response'=>'Selected warehouse was updated.','status'=>'success'));
            } else {

                echo json_encode(array('response' => 'Selected warehouse could not be updated.', 'status' => 'error'));
            }
        }
    }

    public function deleteHouses_post()
    {
        $data = $this->post('models');
        $ids = $data[0]['sn'];

        if (false == $ids) {
            return;
        }
        $ids = explode(',', $ids);
        $ids = array_unique($ids);
        $ids = implode(',', $ids);
        if ($this->db->query("delete from warehouse where sn in ($ids)")) {
            echo json_encode(array('response' => 'Selected warehouses were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected warehouses could not be deleted.', 'status' => 'error'));
        }
    }

    public function deleteHouseMulti_post()
    {
        $id = $this->post('id');

        $ids = implode(',', $id);

        if ($this->db->query("delete from warehouse where sn in ($ids)")) {
            echo json_encode(array('response' => 'Selected warehouse were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected warehouse could not be deleted.', 'status' => 'error'));
        }
    }


    public function category_get()
    {
        $data = array();
        $drop_down = $this->input->get('for');
        $user_id = $this->_userSN;

        if ($drop_down == "dropdown") {
            // $data = $this->db->get_where('category', array('parent_id' => 0, 'adminSN' => $user_id))->result_array();
            $array = array('parent_id' => 0, 'adminSN' => $user_id);
            $this->db->where($array);
            $data = $this->db->get('category')->result_array();
        } else {
            $select = array(
                "name" => "Select Parent",
                'parent_id' => 0
            );
            array_push($data, $select);
            $parent = array(
                "name" => "No Parent",
                'parent_id' => 0
            );
            array_push($data, $parent);
            $main_data = $this->db->get_where('category', array('parent_id' => 0))->result_array();

            for ($i = 0; $i < count($main_data); $i++) {
                array_push($data, $main_data[$i]);
            }
        }

        if (false == (bool)$data) {
            echo json_encode(array());
        } else {
            echo json_encode($data);
        }
    }

    // public function categoryDropDown_get(){
    //     // $data = $this->db->get("category")->result_array();
    //     $data = $this->db->get_where('category')->result_array();
    //     if(false == (bool)$data){
    //         echo json_encode(array());
    //     }else{
    //         echo json_encode($data);
    //     }
    // }

    public function category_detail_get()
    {
        $id = $this->input->get('id');
        $data = $this->db->where('parent_id', $id)->get('category')->result_array();
        if (false == (bool)$data) {
            echo json_encode(array());
        } else {
            echo json_encode($data);
        }
    }

    public function category_post()
    {
        $detail = $this->input->get('edit');
        $data = $this->post('models');

        if ($detail == "detail") {
            $id = $data['id'];
            $parent_name = $data['parent_id'];
            $name = $data['name'];
            $status = $data['status'];
        } else {
            $id = $data[0]['id'];
            $parent_name = $data[0]['parent_id'];
            $name = $data[0]['name'];
            $status = $data[0]['status'];
        }

        if ($parent_name == "No Parent") {
            $parent_id = 0;
        } else {
            if ($detail == "detail") {
                $parent_id = $data['parent_id'];
            } else {
                $get_all_category = $this->db->get_where('category', array('name' => $parent_name))->result_array();
                $parent_id = $get_all_category[0]['id'];
            }
        }


        if ($this->post('action') == 'add' || $id == 0) {
            $insert = array(
                'name' => $data[0]['name'],
                'parent_id' => $parent_id,
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
                'cdUserSN' => $this->_loggedIn,
                'adminSN' => $this->_userSN
            );
            if ($this->db->insert('category', $insert)) {
                $this->response($insert);
                // echo json_encode(array('response'=>'New category was added.','status'=>'success'));
            } else {
                echo json_encode(array('response' => 'New category could not be added.', 'status' => 'error'));
            }
        } elseif ($this->post('action') == 'update' || $id > 0) {

            $update = array(
                'name' => $name,
                'parent_id' => $parent_id,
                'mdUserSN' => $this->_loggedIn,
                'modifiedDate' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', intval($id));
            if ($this->db->update('category', $update)) {
                $update['id'] = $id;
                $this->response($update);
                // echo json_encode(array("response"=>"Category was updated.",'status'=>'success'));
            } else {
                echo json_encode(array("response" => "Category was not updated.", 'status' => 'error'));
            }
        }
    }

    public function deleteCategories_post()
    {
        $data = $this->post('models');
        $detail = $this->input->get('edit');

        if ($detail == "detail") {
            $ids = $data['id'];
        } else {
            $ids = $data[0]['id'];
        }
        if (false == $ids) {
            return;
        }
        $ids = explode(',', $ids);
        $ids = array_unique($ids);
        $ids = implode(',', $ids);
        if ($this->db->query("delete from category where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected categories were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected categories could not be deleted.', 'status' => 'error'));
        }
    }

    public function deleteCategoriesMulti_post()
    {
        $id = $this->post('id');
        $ids = implode(',', $id);

        if ($this->db->query("delete from category where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected categories were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected categories could not be deleted.', 'status' => 'error'));
        }
    }

    public function compatibles_get()
    {
        $user_id = $this->_userSN;

        $data = $this->db->get_where('compatibles', array('adminSN' => $user_id))->result_array();
        if (false == (bool)$data) {
            echo json_encode(array());
        } else {
            echo json_encode($data);
        }
    }

    public function compatibles_post()
    {
        $data = $this->post('models');
        $id = $data[0]['id'];

        if ($this->post('action') == 'add' || $id == 0) {
            $insert = array(
                'name' => $data[0]['name'],
                'status' => $data[0]['status'],
                'cdUserSN' => $this->_loggedIn,
                'adminSN' => $this->_userSN,
                'date_created' => date('Y-m-d H:i:s')
            );
            if ($this->db->insert('compatibles', $insert)) {
                $insert['id'] = $this->db->insert_id();
                $this->response($insert);
                //echo json_encode(array('response'=>"New compatibles added",'status'=>'success'));
            } else {
                echo json_encode(array('response' => "New compatibles could not be added", 'status' => 'error'));
            }
        } elseif ($this->post('action') == 'update' || $id != 0) {

            $update = array(
                'name' => $data[0]['name'],
                'status' => $data[0]['status'],
                'cdUserSN' => $this->_loggedIn,
                'adminSN' => $this->_userSN,
                'mdUserSN' => $this->_loggedIn,
                'modifiedDate' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', intval($id));
            if ($this->db->update('compatibles', $update)) {
                $update['id'] = $id;
                $this->response($update);
                //echo json_encode(array('response'=>"compatibles updated.",'status'=>'success'));
            } else {
                echo json_encode(array('response' => "compatibles could not be updated.", 'status' => 'error'));
            }
        }
    }

    public function deleteCompatibles_post()
    {
        $data = $this->post('models');
        $ids = array();
        foreach ($data as $d) {
            $ids[] = $d['sn'];
        }
        if (empty($ids)) {
            return;
        }
        $ids = array_unique($ids);
        $ids = implode(',', $ids);
        if ($this->db->query("delete from compatibles where sn in ($ids)")) {
            echo json_encode(array('response' => 'Selected compatibles were deleted', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected compatibles could not be deleted.', 'status' => 'error'));
        }
    }

    public function deleteCompatiblesMulti_post()
    {
        $id = $this->post('id');

        $ids = implode(',', $id);

        if ($this->db->query("delete from compatibles where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected compatibles were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected compatibles could not be deleted.', 'status' => 'error'));
        }
    }

    // vendor start
    public function vendors_get()
    {
        $user_id = $this->_userSN;
        $data = $this->db->get_where('vendors', array('adminSN' => $user_id))->result_array();
        if (false == (bool)$data) {
            echo json_encode(array());
        } else {
            echo json_encode($data);
        }
    }

    public function vendors_post()
    {
        $data = $this->post('models');
        $id = $data[0]['id'];

        if ($this->post('action') == 'add' || $id == 0) {
            $insert = array(
                'name' => $data[0]['name'],
                'status' => $data[0]['status'],
                'description' => $data[0]['description'],
                'address' => $data[0]['address'],
                'date_created' => date('Y-m-d H:i:s'),
                'cdUserSN' => $this->_loggedIn,
                'adminSN' => $this->_userSN
            );
            if ($this->db->insert('vendors', $insert)) {
                // echo json_encode(array('response'=>"New vendors added",'status'=>'success'));
                $this->response($insert);
            } else {
                echo json_encode(array('response' => "New vendors could not be added", 'status' => 'error'));
            }
        } elseif ($this->post('action') == 'update' || $id != 0) {

            $update = array(
                'name' => $data[0]['name'],
                'status' => $data[0]['status'],
                'description' => $data[0]['description'],
                'address' => $data[0]['address'],
                'mdUserSN' => $this->_loggedIn,
                'modifiedDate' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', intval($id));
            if ($this->db->update('vendors', $update)) {
                // echo json_encode(array('response'=>"vendors updated.",'status'=>'success'));
                $update['id'] = $id;
                $this->response($update);
            } else {
                echo json_encode(array('response' => "vendors could not be updated.", 'status' => 'error'));
            }
        }
    }

    public function deletevendors_post()
    {
        $data = $this->post('models');
        $ids = $data[0]['id'];
        if (false == $ids) {
            return;
        }
        $ids = explode(',', $ids);
        $ids = array_unique($ids);
        $ids = implode(',', $ids);
        if ($this->db->query("delete from vendors where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected vendors were deleted', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected vendors could not be deleted.', 'status' => 'error'));
        }
    }

    public function deleteVendorsMulti_post()
    {
        $id = $this->post('id');

        $ids = implode(',', $id);

        if ($this->db->query("delete from vendors where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected vendors were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected vendors could not be deleted.', 'status' => 'error'));
        }
    }

    public function testing_get()
    {
        var_dump("fine");
        die();
    }
}