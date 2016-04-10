<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Keepomit extends REST_Controller {

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
    
    function datapost_post() {
        $snArr = explode(",", $this->post('sn'));
        foreach ($snArr as $sn) {
            $dataArr = array(
                'userSN' => $this->_loggedIn,
                'identify' => $this->post('identify'),
                'flag' => $this->post('flag'),
                'selectSN' => $sn
            );
            $this->Admin_Model->saveCommonTable($dataArr, 0, 'keep_omit');
        }
        $this->response(array
            ('Success' => 'Success'), 200);
    }

    function datadelete_post() {
        $dataArr = array(
            'userSN' => $this->_loggedIn, 'identify' => $this->post('identify'),
            'flag' => $this->post('flag')
        );
        //print_r($dataArr);

        $this->Admin_Model->deleteCommonTable($dataArr, 'keep_omit');

        $message = array(
            'message' => 'DELETED!');

        $this->response($message, 200); // 200 being the HTTP response code
    }

}