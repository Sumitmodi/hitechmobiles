<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Slider extends REST_Controller {

    //private $_loggedIn = 0;
    private $_userSN = 0;

    function __construct() {
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

    public function list_get(){
        $data = $this->Admin_Model->getTable('slider', $dataArr = ['adminSN' => $this->_userSN], $pk = 'id', 'SLI');
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    public function edit_post()
    {
        $slider = $this->post('slider');
        
        if (!isset($slider)) {
            $this->response(array('result' => "Slider is not defined.", 'code' => 400));
            return;
        }
        $pro = $slider;
        // $pro['status'] = isset($pro['staus']) ? $pro['status'] : 0;
        if ($this->uri->segment(4) == 0) {
            $action = 'insert';
            unset($pro['photo']);
            $pro['cdUserSN'] = $this->_loggedIn;
            $pro['adminSN'] = $this->_loggedIn;
            $this->db->insert('slider', $pro);
            $pageId = $this->db->insert_id();
        } else {
            $action = 'update';
            unset($pro['image']);
            $pro['modifiedDate'] = date('Y-m-d H:i:s');
            $pro['mdUserSN'] = $this->_loggedIn;
            $this->db->where('id', $this->uri->segment(4))->update('slider', $pro);
            $pageId = $this->uri->segment(4);
        }

        return $this->response(array('response' => 'Slider has been ' . rtrim($action, 'e') . 'ed', 'sliderId' => $pageId,'code'=>200));
    }

    public function upload_post()
    {        
        $sliderId = $this->post('sn');

        if (!isset($_FILES['file'])) {
            return $this->response(array("response" => "File not selected.", 'status' => 400));
        }

        $pro = $this->db->where('id',$sliderId)->select('name')->get('slider')->row();

        if (FALSE == $pro) {
            return $this->response(array("response" => "Slider does not exist.", 'status' => 400));
        }

        $path = ROOTPATH . '/uploads/slider/' . $sliderId;        

        if (!file_exists(ROOTPATH . '/' . $path)) {
            @mkdir($path, 0755, true);
        }        
        $config = [];
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = str_replace(' ', '-', $pro->name) . '-' . $sliderId . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $config['overwrite'] = false;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $this->response(array('response' => $this->upload->display_errors(), 'status' => 400));
        } else {           
            $this->response(array('response' => $this->upload->data(), 'status' => 200));
        }
    }
}