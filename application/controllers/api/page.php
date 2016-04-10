<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Page extends REST_Controller {

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
    
    public function pages_list_get(){
        $user_id = $this->_userSN;
        // $data = $this->db->get_where('pages', array('adminSN' => $user_id))->result_array();
        $data = $this->Admin_Model->getTable('pages',$dataArr=['adminSN'=>$user_id],$pk='id','PG');

        foreach($data as $k=>$d){
            $data[$k]['description'] = substr(strip_tags($d['description']),0,50);
            $data[$k]['meta_description'] = substr(strip_tags($d['meta_description']),0,50);
        }
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    public function pages_post(){
        $data = $this->post('models');
        $id = $data[0]['id'];
        $user_id = $this->_userSN;
        $cd_user = $this->_loggedIn;
        
        if($this->post('action') == 'add' || $id == 0){
            $insert = array(
                'slug_name'   => $data[0]['slug_name'],
                'status' => $data[0]['status'],
                'description' => $data[0]['description'],
                'meta_title' => $data[0]['meta_title'],
                'meta_description' => $data[0]['meta_description'],
                'meta_keywords' => $data[0]['meta_keywords'],
                'adminSN' => $user_id,
                'cdUserSN' => $cd_user
            );
            if($this->db->insert('pages',$insert)){
                // echo json_encode(array('response'=>"New pages added",'status'=>'success'));
                echo json_encode($insert);
            }else{
                echo json_encode(array('response'=>"New pages could not be added",'status'=>'error'));
            }
        }elseif($this->post('action') == 'update' || $id != 0){
            
            $update = array(
                'slug_name'   => $data[0]['slug_name'],
                'status' => $data[0]['status'],
                'description' => $data[0]['description'],
                'meta_title' => $data[0]['meta_title'],
                'meta_description' => $data[0]['meta_description'],
                'meta_keywords' => $data[0]['meta_keywords'],
                'mdUserSN' => $cd_user
            );
            $this->db->where('id',intval($id));
            if($this->db->update('pages',$update)){
                echo json_encode($update);
            }else{
                echo json_encode(array('response'=>"Pages could not be updated.",'status'=>'error'));
            }
        }
    }

    public function edit_post()
    {
        $pages = $this->post('pages');
        
        //prepare product table
        if (!isset($pages)) {
            $this->response(array('result' => "Product is not defined.", 'code' => 400));
            return;
        }
        // var_dump($pages); die();
        $pro = $pages;
        $pro['status'] = isset($pro['staus']) ? $pro['status'] : 0;
        if ($this->uri->segment(4) == 0) {
            $action = 'insert';
            $pro['cdUserSn'] = $this->_loggedIn;
            $pro['adminSN'] = $this->_loggedIn;
            // echo "<pre>";
            // print_r($pro); die();
            $this->db->insert('pages', $pro);
            $pageId = $this->db->insert_id();
        } else {
            $action = 'update';
            $pro['modifiedDate'] = date('Y-m-d H:i:s');
            $this->db->where('id', $this->uri->segment(4))->update('pages', $pro);
            $pageId = $this->uri->segment(4);
        }

        return $this->response(array('response' => 'Page has been ' . rtrim($action, 'e') . 'ed', 'page' => $pageId,'code'=>200));
    }

    public function delete_post(){
        $data = $this->post('models');
        $ids = $data[0]['id'];

        if(false == $ids){
            return;
        }
        $ids = explode(',',$ids);
        $ids = array_unique($ids);
        $ids = implode(',',$ids);
        if($this->db->query("delete from pages where id in ($ids)"))
        {
            echo json_encode(array('response'=>'Selected pages were deleted.','status'=>'success'));
        }else{
            echo json_encode(array('response'=>'Selected pages could not be deleted.','status'=>'error'));
        }
    }

    // public function rest_post()
    // {
    //     if ($this->post('method') !== false) {
    //         $method = $this->post('method');

    //         if ($this->post('action')) {
    //             $method = strtolower($this->post('action')) . ucfirst($method);
    //         }

    //         if (!method_exists($this, $method)) {
    //             $this->response(array('response' => 'Request is invalid. It is not properly configured.', 'code' => 400, 'status' => 'error'));
    //         } else {
    //             $this->$method();
    //         }
    //         return;
    //     }

    //     if (!$this->post('table')) {
    //         $this->response(array('response' => 'Table name not specified with the request.', 'code' => 400, 'status' => 'error'));
    //         return;
    //     }

    //     if ($model = $this->post('model')) {
    //         if (isset($model['where'])) {
    //             foreach ($model['where'] as $key => $where) {
    //                 $this->db->where($key, $where);
    //             }
    //         }

    //         if (isset($model['select']) && $select = $model['select']) {
    //             $this->db->select(implode(',', $select));
    //         }

    //         if (isset($model['where_in'])) {
    //             foreach ($model['where_in'] as $key => $v) {
    //                 $this->db->where_in($key, $v);
    //             }
    //         }

    //         if (isset($model['order_by'])) {
    //             foreach ($model['order_by'] as $col => $order) {
    //                 $this->db->order_by($col, $order);
    //             }
    //         }
    //     }

    //     $result = array();
    //     if (!$this->post('action') || $this->post('action') == 'get') {
    //         $result = $this->db->get($this->post('table'))->result_array();
    //     }
    //     $this->response(array('result' => $result, 'code' => 200,'user'=>$this->_userSN));
    // }

    
}