<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller {

    function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->load->model('Admin_Model');
    }

//function for Login
    function index_post() {
        $user_id = $this->Admin_Model->check_login($this->post('email'), $this->post('password'));
        if (!$user_id) {
            $this->response(array('error' => 'Couldn\'t find any admin!'), 404);
        } else {
            $logindata = array('logged_hitech' => time(), 'logged_hitech_admin_id' => $user_id->sn, 'logged_hitech_adminEmail' => $user_id->email, 'logged_hitech_adminParentSN' => $user_id->parentSN);
            $this->session->set_userdata($logindata);
            $this->response(array('success' => 'OK'), 200);
        }

        //   $this->response(array('error' => 'Domain could not be found'), 404);
    }
    
}