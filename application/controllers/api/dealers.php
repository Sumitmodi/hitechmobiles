<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Dealers extends REST_Controller
{

    //private $_loggedIn = 0;
    private $_userSN = 0;

    function __construct()
    {
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

    public function edit_post()
    {
        $dealer = $this->post('dealers');

        //prepare product table
        if (!isset($dealer)) {
            $this->response(array('result' => "Dealer is not defined.", 'code' => 400));
            return;
        }
        // var_dump($dealer); die();
        $pro = $dealer;
        $pro['status'] = isset($pro['staus']) ? $pro['status'] : 0;
        if ($this->uri->segment(4) == 0) {
            $action = 'insert';
            $pro['cdUserSn'] = $this->_loggedIn;
            $pro['adminSN'] = $this->_loggedIn;
            $this->db->insert('dealer', $pro);
            $pageId = $this->db->insert_id();
        } else {
            $action = 'update';
            $pro['modifiedDate'] = date('Y-m-d H:i:s');
            $this->db->where('id', $this->uri->segment(4))->update('dealer', $pro);
            $pageId = $this->uri->segment(4);
        }

        return $this->response(array(
            'response' => 'Dealer has been ' . rtrim($action, 'e') . 'ed',
            'page' => $pageId,
            'code' => 200
        ));
    }

    public function dealers_list_get()
    {
        $user_id = $this->_userSN;

        $data = $this->db->get_where('dealer', array('adminSN' => $user_id))->result_array();
        end($data);
        $last_key = key($data);
        $last_value = array_pop($data);
        $data = array_merge(array($last_key => $last_value), $data);
        if (false == (bool)$data) {
            echo json_encode(array());
        } else {
            echo json_encode($data);
        }
    }

    public function dealers_insert_post()
    {
        $data = $this->post('models');
        $id = $data[0]['id'];
        $user_id = $this->_userSN;
        $cd_user = $this->_loggedIn;

        if ($this->post('action') == 'add' || $id == 0) {
            $insert = array(
                'first_name' => $data[0]['first_name'],
                'last_name' => $data[0]['last_name'],
                'company_name' => $data[0]['company_name'],
                'email' => $data[0]['email'],
                'phone' => $data[0]['phone'],
                'website' => $data[0]['website'],
                'gst_no' => $data[0]['gst_no'],
                'address' => $data[0]['address'],
                'city' => $data[0]['city'],
                'post_code' => $data[0]['post_code'],
                'country' => $data[0]['country']['name'],
                'interest' => $data[0]['interest'],
                'est_month_handset_expenditure' => $data[0]['est_month_handset_expenditure'],
                'password' => md5($data[0]['password']),
                'status' => $data[0]['status'],
                'adminSN' => $user_id,
                'cdUserSN' => $cd_user
            );
            if ($this->db->insert('dealer', $insert)) {
                // echo json_encode(array('response'=>"New pages added",'status'=>'success'));
                echo json_encode($insert);
            } else {
                echo json_encode(array('response' => "New dealer could not be added", 'status' => 'error'));
            }
        } elseif ($this->post('action') == 'update' || $id != 0) {
            $update = array(
                'first_name' => $data[0]['first_name'],
                'last_name' => $data[0]['last_name'],
                'company_name' => $data[0]['company_name'],
                'email' => $data[0]['email'],
                'phone' => $data[0]['phone'],
                'website' => $data[0]['website'],
                'gst_no' => $data[0]['gst_no'],
                'address' => $data[0]['address'],
                'city' => $data[0]['city'],
                'post_code' => $data[0]['post_code'],
                'country' => $data[0]['country'],
                'interest' => $data[0]['interest'],
                'est_month_handset_expenditure' => $data[0]['est_month_handset_expenditure'],
                'password' => md5($data[0]['password']),
                'status' => $data[0]['status'],
                'mdUserSN' => $cd_user
            );
            $this->db->where('id', intval($id));
            if ($this->db->update('dealer', $update)) {
                echo json_encode($update);
            } else {
                echo json_encode(array('response' => "Dealer could not be updated.", 'status' => 'error'));
            }
        }
    }

    public function delete_post()
    {
        $data = $this->post('models');
        $ids = $data[0]['id'];

        if (false == $ids) {
            return;
        }
        $ids = explode(',', $ids);
        $ids = array_unique($ids);
        $ids = implode(',', $ids);
        if ($this->db->query("delete from dealer where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected dealer were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected dealer could not be deleted.', 'status' => 'error'));
        }
    }


}