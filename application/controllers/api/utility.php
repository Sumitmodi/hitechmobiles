<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Utility extends REST_Controller
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

    public function country_list_get()
    {
        $data = $this->Admin_Model->getTable('country',$dataArr=['adminSN'=>$this->_userSN],$pk='id','CONT');
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    public function country_post()
    {
        $country = $this->post('country');
        $country_id = $country['id'];
        
        //prepare product table
        if (!isset($country)) {
            $this->response(array('result' => "country is not defined.", 'code' => 400));
            return;
        }
        
        if ($country_id == 0) {
            $action = 'insert';
            $country['cdUserSn'] = $this->_loggedIn;
            $country['adminSN'] = $this->_loggedIn;
            unset($country['id']);
            
            $this->db->insert('country', $country);
            $countryTypeId = $this->db->insert_id();
        } else {
            $action = 'update';
            $country['modifiedDate'] = date('Y-m-d H:i:s');
            
            $this->db->where('id', $country_id)->update('country', $country);
            $countryTypeId = $this->uri->segment(4);
        }

        $this->response(array('response' => 'country has been ' . rtrim($action, 'e') . 'ed', 'country' => $countryTypeId,'code'=>200));
    }
}
