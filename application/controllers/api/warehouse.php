<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Customers extends REST_Controller {

    //private $_loggedIn = 0;
    private $_userSN = 0;

    function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->load->model('Admin_Model');
        //$this->load->library('REST_Controller');
        $this->_loggedIn = $this->session->userdata('logged_hitech_admin_id');
        $this->_ParentLoggedIn = $this->session->userdata('logged_hitech_adminParentSN');
        /* if ($this->_loggedIn <= 0 || $this->_ParentLoggedIn <= 0) {
          $this->response(array('error' => 'You are accessing from outside!'), 404);
          exit;
          } */

        if ($this->_ParentLoggedIn == 0) {
            $this->_userSN = $this->_loggedIn;
        } else {
            $this->_userSN = $this->_ParentLoggedIn;
        }
    }
    
    function list_get() {
        $sn = $this->get('sn');
        if (!empty($sn)) {
            $dataArr = array('sn' => $sn);
            $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'warehouse');
        } else {
            $dataArr = array('C', 'O', $this->_loggedIn);
            $resultArr = $this->Admin_Model->getCustomersListByOmitRecords($dataArr);
        }

        if ($resultArr) {
            $this->response($resultArr, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any warehouse!'), 404);
        }
    }

    

    function email_get() {
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

    function attachment_get() {
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

    function data_post() {
        if ($this->post('action') == "General") {
            $customerArr = $this->post('customer');
            $sn = $customerArr['sn'];

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
                $customersemailArr = $this->post('customersemail');
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
        }
        if ($this->post('action') == 'attachment') {
            $attachment = $_FILES['file'];
            $attachmentSN = $this->post('sn');
            $customerSN = $this->post('customerSN');

            $filename = '';
            if (!empty($_FILES['file']['name'])) {
			   //UPload	
                $uploadPath = $this->config->item('customers_upload_path');
                $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'file', "500", "5000", "5000");
				
				if(isset($return['error'])){
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
				 if($this->input->post('description',TRUE) != false)
				 {
					 $dataArr['description'] = $this->input->post('description',TRUE);
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
				 if($this->input->post('description',TRUE) != false)
				 {
					 $dataArr['description'] = $this->input->post('description',TRUE);
				 }
                    $this->Admin_Model->insertCommonTable_GetSN($dataArr, 0, 'customers_attachment');
                }
            }
            $this->response(array('Success'), 200); // 200 being the HTTP response code
        }
    }

    public function remove_post()
    {
        if($this->Admin_Model->deleteCustomers($this->post('lists')))
        {
            $this->response(array('message'=>"Clients deleted.",'status'=>'success'));
        }else{
            $this->response(array('message'=>"Clients not deleted.",'status'=>'error'));
        }
    }


    public function attachmentDelete_post()
    {
        $sn = $this->post('sn');
        if($this->Admin_Model->deleteAttachment($sn))
        {
            $this->response(array('message'=>"Attachment deleted.",'status'=>'success'));
        }else{
            $this->response(array('message'=>"Attachment not deleted.",'status'=>'error'));
        }
    }

    public function attachmentDescUpdate_post()
    {
        $sn = intval($this->post('sn'));
        $desc = FILTER_VAR($this->post('desc'),FILTER_SANITIZE_STRING);
        if($this->Admin_Model->updateDescription($sn,$desc))
        {
            $this->response(array('message'=>"Description updated.",'status'=>'success'));
        }else{
            $this->response(array('message'=>"Description could not be updated.",'status'=>'error'));
        }
    }

    public function deleteEmail_post()
    {
        $sn = intval($this->post('sn'));
        if($this->Admin_Model->deleteEmail($sn))
        {
            $this->response(array('message'=>"Email deleted.",'status'=>'success'));
        }else{
            $this->response(array('message'=>"Email could not be deleted.",'status'=>'error'));
        }
    }

    public function warehouse_get(){
        $data = $this->db->get("warehouse")->result_array();
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    public function warehouse_post(){
        if($this->post('action') == 'add' || $this->post('sn') == 0){
           $insert = array(
               'name'   => $this->post('name'),
               'description'    => $this->post('description'),
               'status' => $this->post('status'),
               'adminSN' => $this->_loggedIn,
               'createdDate' => date('Y-m-d H:i:s')
           );
            if($this->db->insert('warehouse',$insert)){
                echo json_encode(array('response'=>'New warehouse was added.','status'=>'success'));
            }else{
                echo json_encode(array('response'=>'New warehouse could not be added.','status'=>'error'));
            }
        }elseif($this->post('action') == 'update'){
            if(strtolower($this->post('status')) == 'yes'){
                $status = 'Y';
            }elseif(strtolower($this->post('status')) == 'no'){
                $status = 'N';
            }else{
                $status = $this->post('status');
            }
            $update = array(
                'name'   => $this->post('name'),
                'description'    => $this->post('description'),
                'status' => $status,
                'mdUserSN' => $this->_loggedIn,
                'modifiedDate' => date('Y-m-d H:i:s')
            );
            $this->db->where('sn',intval($this->post('sn')));
            if($this->db->update('warehouse',$update)){

                echo json_encode(array('response'=>'Selected warehouse was updated.','status'=>'success'));
            }else{

                echo json_encode(array('response'=>'Selected warehouse could not be updated.','status'=>'error'));
            }
        }
    }

    public function deleteHouses_post(){
        $ids = $this->post('snos');
        if(false == $ids){
            return;
        }
        $ids = explode(',',$ids);
        $ids = array_unique($ids);
        $ids = implode(',',$ids);
        if($this->db->query("delete from warehouse where sn in ($ids)"))
        {
            echo json_encode(array('response'=>'Selected warehouses were deleted.','status'=>'success'));
        }else{
            echo json_encode(array('response'=>'Selected warehouses could not be deleted.','status'=>'error'));
        }
    }


    public function category_get(){
        $data = $this->db->get("category")->result_array();
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    public function category_post(){
        if($this->post('action') == 'add' || $this->post('id') == 0){
            $insert = array(
                'name'   => $this->post('name'),
                'parent_id'    => $this->post('parent_id'),
                'status' => $this->post('status')
            );
            if($this->db->insert('category',$insert)){
                echo json_encode(array('response'=>'New category was added.','status'=>'success'));
            }else{
                echo json_encode(array('response'=>'New category could not be added.','status'=>'error'));
            }
        }elseif($this->post('action') == 'update'){
            if(strtolower($this->post('status')) == 'yes'){
                $status = 'Y';
            }elseif(strtolower($this->post('status')) == 'no'){
                $status = 'N';
            }else{
                $status = $this->post('status');
            }
            $update = array(
                'name'   => $this->post('name'),
                'parent_id'    => $this->post('parent_id'),
                'status' => $status
            );
            $this->db->where('id',intval($this->post('id')));
            if($this->db->update('category',$update)){
                echo json_encode(array("response"=>"Category was updated.",'status'=>'success'));
            }else{
                echo json_encode(array("response"=>"Category was not updated.",'status'=>'error'));
            }
        }
    }

    public function deleteCategories_post(){
        $ids = $this->post('snos');
        if(false == $ids){
            return;
        }
        $ids = explode(',',$ids);
        $ids = array_unique($ids);
        $ids = implode(',',$ids);
        if($this->db->query("delete from category where id in ($ids)"))
        {
            echo json_encode(array('response'=>'Selected categories were deleted.','status'=>'success'));
        }else{
            echo json_encode(array('response'=>'Selected categories could not be deleted.','status'=>'error'));
        }
    }

    public function vendors_get(){
        $data = $this->db->get("vendors")->result_array();
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    public function vendor_post(){
        if($this->post('action') == 'add' || $this->post('id') == 0){
            $insert = array(
                'name'   => $this->post('name'),
                'status' => $this->post('status')
            );
            if($this->db->insert('vendors',$insert)){
                echo json_encode(array('response'=>"New vendor added",'status'=>'success'));
            }else{
                echo json_encode(array('response'=>"New vendor could not be added",'status'=>'error'));
            }
        }elseif($this->post('action') == 'update'){
            if(strtolower($this->post('status')) == 'yes'){
                $status = 'Y';
            }elseif(strtolower($this->post('status')) == 'no'){
                $status = 'N';
            }else{
                $status = $this->post('status');
            }
            $update = array(
                'name'   => $this->post('name'),
                'status' => $status
            );
            $this->db->where('id',intval($this->post('id')));
            if($this->db->update('vendors',$update)){
                echo json_encode(array('response'=>"Vendor updated.",'status'=>'success'));
            }else{
                echo json_encode(array('response'=>"Vendor could not be updated.",'status'=>'error'));
            }
        }
    }

    public function deleteVendors_post(){
        $ids = $this->post('snos');
        if(false == $ids){
            return;
        }
        $ids = explode(',',$ids);
        $ids = array_unique($ids);
        $ids = implode(',',$ids);
        if($this->db->query("delete from vendors where id in ($ids)"))
        {
            echo json_encode(array('response'=>'Selected vendors were deleted','status'=>'success'));
        }else{
            echo json_encode(array('response'=>'Selected vendors could not be deleted.','status'=>'error'));
        }
    }
}