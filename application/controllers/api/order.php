<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Order extends REST_Controller {

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
    
    // order listing
    
    public function order_list_get(){
        $data = $this->Admin_Model->getTable('orders',$dataArr=['adminSN'=>$this->_userSN],$pk='id','O');

        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }
    
    public function order_nodes_list_get(){
        $id = $this->input->get('id');
        $dataArr = array('order_id' => $id);
        $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'order_notes');

        if ($resultArr) {
            $this->response($resultArr, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any order notes!'), 404);
        }
    }

    // order edit
    public function edit_post()
    {
        $order = $this->post('order');
        
        
        //prepare product table
        if (!isset($order)) {
            $this->response(array('result' => "Order is not defined.", 'code' => 400));
            return;
        }

        // implode product id
        /*$pro = $order['product'];
        $product_id = [];
        for ($i=0; $i < count($pro); $i++) { 
            array_push($product_id, $pro[$i]['id']);
        }
        $order['product_id'] = implode(', ', $product_id);
        unset($order['product']);*/
        // end implode
        $order['product_id'] = $order['product'][0]['name'];
        unset($order['product']);
        unset($order['note']);

        $order['customer'] = $order['customer'][0]['name'];
        
        if ($this->uri->segment(4) == 0) {
            $action = 'insert';
            $order['cdUserSn'] = $this->_loggedIn;
            $order['adminSN'] = $this->_loggedIn;

            $this->db->insert('orders', $order);
            $proId = $this->db->insert_id();
        } else {
            $action = 'update';
            $order['modified_date'] = date('Y-m-d H:i:s');
            
            $this->db->where('id', $this->uri->segment(4))->update('orders', $order);
            $proId = $this->uri->segment(4);
        }

        $this->response(array('response' => 'Order has been ' . rtrim($action, 'e') . 'ed', 'product' => $proId,'code'=>200));
    }
    
    // order note edit
    public function note_edit_post()
    {
        $order = $this->post('order');
        $order_id = $order['note']['order_id'];
        //prepare product table
        if (!isset($order['note'])) {
            $this->response(array('result' => "Order is not defined.", 'code' => 400));
            return;
        }

        
        if ($this->uri->segment(4) == 0) {
            $action = 'insert';
            $this->db->insert('order_notes', $order['note']);
            $proId = $this->db->insert_id();
        } else {
            $action = 'update';
            $this->db->where('id', $this->uri->segment(4))->update('order_notes', $order['note']);
            $proId = $this->uri->segment(4);
        }

        // $this->response(array('response' => 'Order note has been ' . rtrim($action, 'e') . 'ed', 'order note' => $proId,'code'=>200));
        $this->response(array('response' => 'Order note has been ' . rtrim($action, 'e') . 'ed', 'order_id' => $order_id,'code'=>200));
    }

    // payment type start
    public function product_types_get(){
        $user_id = $this->_userSN;
        $data = $this->db->get_where('payment_type', array('adminSN' => $user_id))->result_array();
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    // shipping type start
    public function shipping_type_get(){
        $user_id = $this->_userSN;
        $data = $this->db->get_where('shipping_type', array('adminSN' => $user_id))->result_array();
        if(false == (bool)$data){
            echo json_encode(array());
        }else{
            echo json_encode($data);
        }
    }

    // payment type data insert
    public function product_types_post(){
        $data = $this->post('models');
        $id = $data[0]['id'];

        if($this->post('action') == 'add' || $id == 0){
            $insert = array(
                'type'   => $data[0]['type'],
                'cdUserSn'  => $this->_loggedIn,
                'adminSN'   =>  $this->_userSN
            );
            if($this->db->insert('payment_type',$insert)){
                $insert['sn'] = $this->db->insert_id();
                echo json_encode($insert);
                // echo json_encode(array('response'=>"New payment type added",'status'=>'success'));
            }else{
                echo json_encode(array('response'=>"New payment type could not be added",'status'=>'error'));
            }
        }elseif($this->post('action') == 'update' || $id != 0){
            
            $update = array(
                'type'   => $data[0]['type'],
                'mdUserSn'  => $this->_loggedIn,
                'modified_date  '  => date('Y-m-d H:i:s')
            );
            $this->db->where('id',intval($id));
            if($this->db->update('payment_type',$update)){
                echo json_encode($update);
            }else{
                echo json_encode(array('response'=>"Paymnet could not be updated.",'status'=>'error'));
            }
        }
    }

    // shipping type data insert
    public function shipping_type_post(){
        $data = $this->post('models');
        $id = $data[0]['id'];

        if($this->post('action') == 'add' || $id == 0){
            $insert = array(
                'type'   => $data[0]['type'],
                'description'   => $data[0]['description'],
                'cdUserSn'  => $this->_loggedIn,
                'adminSN'   =>  $this->_userSN
            );
            if($this->db->insert('shipping_type',$insert)){
                $insert['sn'] = $this->db->insert_id();
                echo json_encode($insert);
                // echo json_encode(array('response'=>"New payment type added",'status'=>'success'));
            }else{
                echo json_encode(array('response'=>"New Shipping type could not be added",'status'=>'error'));
            }
        }elseif($this->post('action') == 'update' || $id != 0){
            
            $update = array(
                'type'   => $data[0]['type'],
                'description'   => $data[0]['description'],
                'mdUserSn'  => $this->_loggedIn,
                'modified_date  '  => date('Y-m-d H:i:s')
            );
            $this->db->where('id',intval($id));
            if($this->db->update('shipping_type',$update)){
                echo json_encode($update);
            }else{
                echo json_encode(array('response'=>"Shipping could not be updated.",'status'=>'error'));
            }
        }
    }

    // shipping type delete
    public function delete_shipping_type_post(){
        $data = $this->post('models');
        $ids = $data[0]['id'];
        if(false == $ids){
            return;
        }
        $ids = explode(',',$ids);
        $ids = array_unique($ids);
        $ids = implode(',',$ids);
        if($this->db->query("delete from shipping_type where id in ($ids)"))
        {
            echo json_encode(array('response'=>'Selected shipping type were deleted','status'=>'success'));
        }else{
            echo json_encode(array('response'=>'Selected shipping type could not be deleted.','status'=>'error'));
        }
    }

    // payment type delete
    public function delete_product_type_post(){
        $data = $this->post('models');
        $ids = $data[0]['id'];
        if(false == $ids){
            return;
        }
        $ids = explode(',',$ids);
        $ids = array_unique($ids);
        $ids = implode(',',$ids);
        if($this->db->query("delete from payment_type where id in ($ids)"))
        {
            echo json_encode(array('response'=>'Selected product type were deleted','status'=>'success'));
        }else{
            echo json_encode(array('response'=>'Selected product type could not be deleted.','status'=>'error'));
        }
    }

    public function deleteVendorsMulti_post(){
        // $id = $this->post('id');
        
        // $ids = implode(',',$id);
        
        // if($this->db->query("delete from vendors where id in ($ids)"))
        // {
        //     echo json_encode(array('response'=>'Selected vendors were deleted.','status'=>'success'));
        // }else{
        //     echo json_encode(array('response'=>'Selected vendors could not be deleted.','status'=>'error'));
        // }
    }
}