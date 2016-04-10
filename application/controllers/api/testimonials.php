<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Testimonials extends REST_Controller
{
    private $_userSN = 0;

    public function __construct()
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

    public function list_get()
    {
        $data = $this->Admin_Model->getTable('testimonials', $dataArr = ['adminSN' => $this->_userSN], $pk = 'sn', 'T');
        $response = array();
        if (false != (bool)$data) {
            foreach ($data as $d) {
                $d = (object)$d;
                $temp = array();
                $where = array('sn' => $d->cust_sn);
                $customer = $this->Admin_Model->getCommonTable($where, 'customers', '', '', array('name'), '1');
                if (false != $customer) {
                    $temp['customer'] = isset($customer[0]) ? $customer[0]['name'] : $customer['name'];
                } else {
                    $temp['customer'] = 0;
                }
                $temp['feedback'] = $d->feedback;
                $temp['created_date'] = $d->createdDate;
                $temp['status'] = $d->status;
                $temp['sn'] = $d->sn;
                $response[] = $temp;
            }
        }
        echo json_encode($response);
    }

    public function testi_post()
    {
        $t = $this->post('testi');
        $sno = $t['sn'];
        unset($t['sn']);
        if ($sno == 0) {
            $t['createdDate'] = date('Y-m-d H:i:s', strtotime($t['createdDate']));
            $t['adminSN'] = $this->_userSN;
            $t['cdUserSN'] = $this->_loggedIn;
        } else {
            $t['modifiedDate'] = date('Y-m-d');
            $t['createdDate'] = date('Y-m-d H:i:s', strtotime($t['createdDate']));
            $t['mdUserSN'] = $this->_loggedIn;
        }
        if ($this->Admin_Model->saveCommonTable($t, $sno, 'testimonials')) {
            $this->response(array('response' => 'success', 'code' => 200));
        } else {
            $this->response(array('response' => 'error', 'code' => 400));
        }
    }
}