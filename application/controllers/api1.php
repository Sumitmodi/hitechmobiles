<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {

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

    

    

    
    function domain_get() {
        if (!$this->get('sn')) {
            $this->response(array('error' => 'SN not defined'), 404);
        }
        //Get Domain List Based on SN
        $domain = $this->Api_Model->getDomainList($this->get('sn'));
        if ($domain) {
            $this->response($domain, 200); // 200 being the HTTP response code
        } else {
            $this->response(array
                ('error' => 'Domain could not be found'), 404);
        }
    }

    function ping_get() {
        if (!$this->get('hosts')) {
            $this->response(array('error' => 'Domain not defined'), 404);
        }

        $hosts = explode(",", $this->get('hosts'));
        $hostsArr = array();
        foreach ($hosts as $domainName) {
            $status = $this->singleping($domainName);
            $hostsArr[$domainName] = ($status < 0 ? 'NULL' : $status);
        }
        if ($hostsArr) {
            $this->response($hostsArr, 200); // 200 being the HTTP response code
        } else {
            $this->response(array
                ('error' => 'No Domains!'), 404);
        }
    }

    function singleping($domain) {
        $starttime = microtime(true);
        // supress error messages with @
        $file = @

                fsockopen($domain, 80, $errno, $errstr, 10);
        $stoptime = microtime(true);
        $status = 0;

        if (!$file) {
            $status = -1;  // Site is down
        } else {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
        return $status;
    }

    function domain_post() {
        $dataArr = array('sn' => $this->get('sn'),
            'name' => $this->post('name'),
            'status' => 'Y',
            'createdDate' => date("Y-m-d H:i:s")
        );
        //Get Domain List Based on SN
        $domain = $this->Api_Model->saveData($dataArr, $this->get('sn'), 'en_domains');
        if ($domain) {
            $message = array(
                'sn' => $domain['sn'], 'name' => $this->post('name'), 'message' => 'ADDED!');

            $this->response($domain, 200);
        }
    }

    function domain_put() {
        $dataArr = array('name' => $this->input->get('name')
        );

        //Get Domain List Based on SN
        $domain = $this->Api_Model->saveData($dataArr, $this->get('sn'), 'en_domains');
        if ($domain) {
            $message = array('sn' => $this->get('sn'), 'name' => $this->post('name'), 'message' => 'ADDED!');

            $this->response($domain, 200);
        } else {
            $this->response(array('error' => 'Domain could not be found'), 200);
        }
    }

}
