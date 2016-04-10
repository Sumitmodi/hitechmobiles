<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Prodlocations extends REST_Controller
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

    function list_get()
    {
        $sn = $this->get('sn');

        if (!empty($sn)) {
            $dataArr = array('id' => $sn, 'adminSN' => $this->_userSN);
            $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'products_locations');
        } else { 
            $resultArr = $this->db->get_where('products_locations', array('adminSN' => $this->_userSN))->result_array();

        }

        if ($resultArr) {
            // $this->response($resultArr, 200); // 200 being the HTTP response code
            echo json_encode($resultArr);
        } else {
            $this->response(array('error' => 'Couldn\'t find any productsLocations!'), 404);
        }
    }

    public function product_post()
    {
        $data = $this->post('models')[0];

        $id = $data['id'];
        if ($_GET['action'] == 'add' || $_GET['action'] == 'create') {
            $insert = array(
                'code' => $data['code'],
                'status' => $data['status'],
                'description' => $data['description'],
                'warehouseSN' => isset($data['warehouseSN']['sn']) ? $data['warehouseSN']['sn']:$data['warehouseSN'],
                'createdDate'   => date('Y-m-d H:i:s'),
                'cdUserSN'  => $this->_loggedIn,
                'adminSN'   =>  $this->_userSN
            );
            if ($this->db->insert('products_locations', $insert)) {
                $insert['id'] = $this->db->insert_id();
                echo json_encode($insert);
            }
        } elseif ($_GET['action'] == 'update') {

            $update = array(
                'code' => $data['code'],
                'status' => $data['status'],
                'description' => $data['description'],
                'warehouseSN' => isset($data['warehouseSN']['sn']) ? $data['warehouseSN']['sn']:$data['warehouseSN'],
                'mdUserSN'  => $this->_loggedIn,
                'modifiedDate'  => date('Y-m-d H:i:s')
            );
            $this->db->where('id', intval($id));
            if ($this->db->update('products_locations', $update)) {
                echo json_encode($update);
            }
        }
    }

    public function deleteProduct_post()
    {
        $data = $this->post('models');
        $ids = $data[0]['id'];
        if (false == $ids) {
            return;
        }
        $ids = explode(',', $ids);
        $ids = array_unique($ids);
        $ids = implode(',', $ids);
        if ($this->db->query("delete from products_locations where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected products locations were deleted', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected products locations could not be deleted.', 'status' => 'error'));
        }
    }

    public function deleteProductMulti_post()
    {
        $id = $this->post('id');

        $ids = implode(',', $id);

        if ($this->db->query("delete from products_locations where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected products locations were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected products locations could not be deleted.', 'status' => 'error'));
        }
    }

}