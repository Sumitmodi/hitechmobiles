<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Jobs extends REST_Controller
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

    public function jobs_list_get()
    {
        $sql = "SELECT customers.sn, customers.name, customers.createdDate,j.id, j.make, j.model, customers.address FROM jobs as j LEFT JOIN customers ON j.customer_id=customers.sn";
        $result = $this->db->query($sql);
        $data = $result->result_array();
        $jobData = $data;
        $value = array();

        $sql1 = "SELECT * FROM keep_omit where identify = 'J'";
        $result1 = $this->db->query($sql1);
        $data1 = $result1->result_array();

        if (!empty($data1)) {
            for ($j=0; $j < count($data1); $j++) {
                for ($i=0; $i < count($jobData) ; $i++) { 
                    if ($jobData[$i]['sn'] == $data1[$j]['selectSN']) {
                        unset($data[$i]);
                    } else {
                        array_push($value, $data[$i]);
                    }
                }
            }
        } else {
            $value = $data;
        }

        end($value);
        $last_key     = key($value);
        $last_value   = array_pop($value);
        $value          = array_merge(array($last_key => $last_value), $value);

        if(false == (bool)$value){
            echo json_encode(array());
        }else{
            echo json_encode($value);
        }
    }

    public function comments_list_get(){
        $id = $this->input->get('id');
        $dataArr = array('customer_id' => $id);
        $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'comments');

        if ($resultArr) {
            $this->response($resultArr, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any comments!'), 404);
        }
    }

    public function edit_post()
    {
        $customer = $this->post('customer');
        $customer_id = $customer['customer_id'];
        
        //prepare product table
        if (!isset($customer['customer'])) {
            $this->response(array('result' => "Customer is not defined.", 'code' => 400));
            return;
        }
        
        if ($customer_id == 0) {
            $action = 'insert';
            $customer['customer']['cdUserSN'] = $this->_loggedIn;
            $customer['customer']['adminSN'] = $this->_loggedIn;
            if (!empty($customer['customer']['sn'])) {
                $customer['jobs']['customer_id'] = $customer['customer']['sn'];
                unset($customer['jobs']['id']);
            } else {
                $this->db->insert('customers', $customer['customer']);
                $customerId = $this->db->insert_id();
                $customer['jobs']['customer_id'] = $customerId;
            }
           
            $this->db->insert('jobs', $customer['jobs']);
        } else {
            $action = 'update';
            $customer['customer']['modifiedDate'] = date('Y-m-d H:i:s');
            
            $this->db->where('id', $customer_id)->update('customers', $customer['customer']);
            $this->db->where('customer_id', $customer_id)->update('customers', $customer['customer']['jobs']);
            $customerId = $this->uri->segment(4);
        }

        //$this->response(array('response' => 'Customer has been ' . rtrim($action, 'e') . 'ed', 'product' => $customerId,'code'=>200));
    }

    public function comments_edit_post() {
        $comments = $this->post('comments');        
        $customer_id = $comments['id'];
        
        //prepare product table
        if (!isset($comments)) {
            $this->response(array('result' => "Customer is not defined.", 'code' => 400));
            return;
        }

        if ($customer_id > 0) {
            $action = 'insert';            
            $comments['customer_id'] = $customer_id;
            unset($comments['id']);

            $this->db->insert('comments', $comments);
            $commentId = $this->db->insert_id();
            if ($commentId) {
                $this->response(array('response' => 'Comment has been ' . rtrim($action, 'e') . 'ed', 'customer_id' => $customer_id,'code'=>200));
            }
        } 
    }

    
    // device type start
    public function device_type_list_get(){
        $data = $this->Admin_Model->getTable('device_type',$dataArr=['adminSN'=>$this->_userSN],$pk='id','DT');
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    public function device_type_post()
    {
        $device = $this->post('device');
        $device_type_id = $device['id'];
        
        //prepare product table
        if (!isset($device)) {
            $this->response(array('result' => "Device is not defined.", 'code' => 400));
            return;
        }
        
        if ($device_type_id == 0) {
            $action = 'insert';
            $device['cdUserSn'] = $this->_loggedIn;
            $device['adminSN'] = $this->_loggedIn;
            unset($device['id']);
            
            $this->db->insert('device_type', $device);
            $deviceTypeId = $this->db->insert_id();
        } else {
            $action = 'update';
            $device['modifiedDate'] = date('Y-m-d H:i:s');
            
            $this->db->where('id', $device_type_id)->update('device_type', $device);
            $deviceTypeId = $this->uri->segment(4);
        }

        $this->response(array('response' => 'Device has been ' . rtrim($action, 'e') . 'ed', 'product' => $deviceTypeId,'code'=>200));
    }
}
