<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller
{
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

    function rest_post()
    {
        if ($this->post('method') !== false) {
            $method = $this->post('method');
            if ($this->post('action')) {
                $method = strtolower($this->post('action')) . ucfirst($method);
            }
            if (!method_exists($this, $method)) {
                $this->response(array(
                    'response' => 'Request is invalid. It is not properly configured.',
                    'code' => 400,
                    'status' => 'error'
                ));
            } else {
                $this->$method();
            }
            return;
        }
        if (!$this->post('table')) {
            $this->response(array(
                'response' => 'Table name not specified with the request.',
                'code' => 400,
                'status' => 'error'
            ));
            return;
        }
        if ($model = $this->post('model')) {
            if (isset($model['where'])) {
                foreach ($model['where'] as $key => $where) {
                    if (isset($where['method'])) {
                        list($method, $args) = explode('|', $where['method']);
                        $method = varStr($method);
                        $args = varStr($args);
                        if (method_exists($this, $method)) {
                            if (isset($args)) {
                                $where = $this->$method($args);
                            } else {
                                $where = $this->$method();
                            }
                        } else {
                            if (function_exists($method)) {
                                $where = call_user_func_array($method,
                                    isset($args) ? (is_array($args) ? $args : array($args)) : array());
                            } else {
                                continue;
                            }
                        }

                        $this->db->where($key, $where);
                    } else {
                        $this->db->where($key, $where);
                    }
                }
            }
            if (isset($model['select']) && $select = $model['select']) {
                $this->db->select(implode(',', $select));
            }
            if (isset($model['where_in'])) {
                foreach ($model['where_in'] as $key => $v) {
                    $this->db->where_in($key, $v);
                }
            }
            if (isset($model['order_by'])) {
                foreach ($model['order_by'] as $col => $order) {
                    $this->db->order_by($col, $order);
                }
            }
        }
        $result = array();
        if (!$this->post('action') || $this->post('action') == 'get') {
            $result = $this->db->get($this->post('table'))->result_array();
        }
        if ($this->post('action') == 'delete') {
            if (!$this->db->delete($this->post('table'))) {
                $code = 400;
            }
        }
        if ($this->post('action') == 'update') {
            if (!isset($model['update'])) {
                $code = 400;
                $result = 'Fields to be updated have not been defined.';
            } else {
                $this->db->update($this->post('table'), $model['update']);
            }
        }
        $this->response(array('result' => $result, 'code' => isset($code) ? $code : 200));
    }

    //get class variable
    private function getVar($var)
    {
        if (!is_array($var)) {
            return $this->$var;
        }
        return false;
    }

    private function deleteCustomers()
    {
        $ids = $this->post('id');
        if (false == $this->post('multiple') && count($ids) > 1) {
            return $this->response(array('result' => 'Request is not configured correctly.', 'status' => 200));
        }
        $id = implode(", ", $ids);
        //get customers attachments
        $attachments = $this->db->query("select file from customers_attachment where customerSN in ($id)")->result_object();
        if (!empty($attachments)) {
            //delete attachments
            foreach ($attachments as $a) {
                if (file_exists(ROOTPATH . '/uplaods/customers/' . $a->file)) {
                    @unlink(ROOTPATH . '/uplaods/customers/' . $a->file);
                }
            }
            $this->db->query("delete from customers_attachment where customerSN in ($id)");
        }
        //delete customers emails
        $this->db->query("delete from customers_email where customerSN in ($id)");
        //finally remove them from the database
        if ($this->db->query("delete from customers where sn in ($id)")) {
            $this->response(array('response' => "Selected customers were deleted from the database.", 'status' => 200));
        } else {
            $this->response(array(
                'response' => "Something went wrong while trying to delete customers. Please try later.",
                'status' => 400
            ));//400 is not supposed to be valid response code for this response
        }
    }

    //omit selected from the grid
    private function selection()
    {
        if (false == $this->post('flag')) {
            return $this->response(array("Request is not constructed properly.", 'status' => 400));
        }
        //'PL','C','O','W','CAT','CB','V','PG','DL','P','T','J','DT','CONT','SLI','PUR'
        $tables_map = [
            'PL' => 'products_locations',
            'C' => 'customers',
            'O' => 'orders',
            'W' => 'warehouse',
            'CAT' => 'category',
            'CB' => 'compatibles',
            'V' => 'vendors',
            'PG' => 'pages',
            'DL' => 'dealer',
            'P' => 'products',
            'J' => 'jobs',
            'DT' => 'device_type',
            'CONT' => 'country',
            'T' => 'testimonials',
            'SLI' => 'slider',
            'PUR' => 'purchases'
        ];
        $id_map = [
            'PL' => 'sn',
            'C' => 'sn',
            'O' => 'sn',
            'W' => 'sn',
            'CAT' => 'sn',
            'CB' => 'id',
            'V' => 'id',
            'PG' => 'id',
            'DL' => 'id',
            'P' => 'id',
            'J' => 'id',
            'DT' => 'id',
            'CONT' => 'id',
            'T' => 'sn',
            'SLI' => 'id',
            'PUR' => 'id'
        ];
        $flag = strtoupper($this->post('flag'));
        if (strlen($flag) > 1) {
            $flag = $flag[0];
        }
        $ids = array_map('intval', $this->post('id'));//_userSN
        $id = implode(',',$ids);
        if ($flag == "K") {
            $res = $this->db->where_not_in($id_map[strtoupper($this->post('type'))],
                $ids)->get($tables_map[strtoupper($this->post('type'))])->result_array();
            if (false == $res) {
                $flag = $flag == 'K' ? 'keep' : 'omit';
                $this->response(['response' => "Requested action {$flag} is not required.", 'status' => 200]);
                return;
            }
            $t = [];
            foreach ($res as $r) {
                $t[] = $r[$id_map[strtoupper($this->post('type'))]];
            }
            $ids = $t;
            $id = implode(',', $ids);
        }
        $added = $this->db->query("select selectSN from keep_omit where selectSN in ($id) and userSN = {$this->_userSN} and identify = 'O'")->result_object();
        $exist = array();
        if (!empty($added)) {
            foreach ($added as $a) {
                $exist[] = $a->selectSN;
            }
        }

        foreach ($ids as $i) {
            $query = array(
                'userSN' => $this->_userSN,
                'selectSN' => $i,
                'identify' => strtoupper($this->post('type')),
                'flag' => "O"
            );

            if (in_array($i, $exist)) {
                $this->db->where('selectSN', $i)->where('userSN', $this->_userSN)->where('identify',
                    $this->post('type'))->update('keep_omit', $query);
            } else {
                $this->db->insert('keep_omit', $query);
            }
        }
        $flag = $this->post('flag') == 'K' ? 'keep' : $this->post('flag');
        $this->response(['response' => "Requested action {$flag} was completed successfully.", 'status' => 200]);
    }

    private function deleteCategories()
    {
        $ids = $this->post('id');
        if (false == $this->post('multiple') && count($ids) > 1) {
            return $this->response(array('result' => 'Request is not configured correctly.', 'status' => 200));
        }
        $id = implode(", ", $ids);
        if ($this->db->query("delete from category where id in ($id) or parent_id in ($id)")) {
            $this->response(array('response' => "Category was delete successfully.", 'status' => 200));
        } else {
            $this->response(array('response' => "Category could not deleted.", 'status' => 400));
        }
    }

    private function deletePurchases()
    {
        $ids = $this->post('id');
        if (false == $this->post('multiple') && count($ids) > 1) {
            return $this->response(array('response' => 'Request is not configured correctly.', 'status' => 200));
        }

        if ($this->db->where_in('id', $ids)->delete('purchases')) {
            $this->db->where_in('purchase_id', $ids)->delete('purchase_shipping');
            $this->db->where_in('purchase_id', $ids)->delete('purchase_itemss');
            $this->response(array('response' => 'Purchases has been deleted successfully..', 'status' => 200));
        } else {
            $this->response(array('response' => 'Purchases could not be deleted.', 'status' => 40));
        }
    }
}