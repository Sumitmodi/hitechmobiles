<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Purchases extends REST_Controller
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

    public function list_get()
    {
        $sub = "(SELECT COUNT(*) FROM purchase_items WHERE purchase_items.purchase_id = purchases.id) as items";
        $vendor = "(select name from vendors where vendors.id = purchases.vendor) as vendor_name";
        $sql = "select *,$sub,$vendor from `purchases` where adminSN = $this->_userSN and purchases.id not in (select selectSN from keep_omit where userSN=$this->_userSN and identify = 'PUR' and flag='O')";

        $res = $this->db->query($sql);

        if ($res->num_rows() == 0) {
            return $this->response(['response' => 'No purchases have been made.']);
        }

        $resultArr = $res->result_array();

        end($resultArr);
        $last_key = key($resultArr);
        $last_value = array_pop($resultArr);
        $resultArr = array_merge(array($last_key => $last_value), $resultArr);

        return $this->response($resultArr);
    }

    public function edit_post()
    {
        $purchase = $this->post('purchase');
        $shipping = isset($purchase['shipping']) ? $purchase['shipping'] : array();
        $items = isset($purchase['items']) ? $purchase['items'] : array();

        if (empty($items)) {
            return $this->response(array('response' => 'Items in purchase list is empty.', 'code' => 400));
        }

        if (empty($shipping)) {
            return $this->response(array('response' => 'Shipping info must be defined.', 'code' => 400));
        }

        unset($purchase['shipping']);
        unset($purchase['items']);

        $id = $purchase['id'];
        unset($purchase['id']);

        $insert = [
            'name' => isset($purchase['name']) ? $purchase['name'] : null,
            'vendor' => isset($purchase['vendor']) ? $purchase['vendor'] : 0,
            'print_report' => isset($purchase['print_report']) ? $purchase['print_report'] : 0,
            'order_date' => isset($purchase['order_date']) ? date('Y-m-d',strtotime($purchase['order_date'])) : null,
            'type' => isset($purchase['type']) ? $purchase['type'] : 0,
            'due_date' => isset($purchase['due_date']) ? date('Y-m-d',strtotime($purchase['due_date'])) : null,
            'invoice_no' => isset($purchase['invoice_no']) ? $purchase['invoice_no'] : 0,
            'note' => isset($purchase['note']) ? $purchase['note'] : null,
            'status' => isset($purchase['status']) ? $purchase['status'] : 0,
            'paid_status' => isset($purchase['paid_status']) ? $purchase['paid_status'] : 0,
            'status_date' => isset($purchase['status_date']) ? date('Y-m-d',strtotime($purchase['status_date'])) : null,
            'assigned_to' => isset($purchase['assigned_to']) ? $purchase['assigned_to'] : null,
            'net_total' => isset($purchase['net_total']) ? $purchase['net_total'] : 0,
            'discount' => isset($purchase['discount']) ? $purchase['discount'] : 0,
            'discount_per' => isset($purchase['discount_per']) ? $purchase['discount_per'] : 0,
            'tax' => isset($purchase['tax']) ? $purchase['tax'] : 0,
            'tax_per' => isset($purchase['tax_per']) ? $purchase['tax_per'] : 0,
            'total' => isset($purchase['total']) ? $purchase['total'] : 0,
            'receive_date' => isset($purchase['receive_date']) ? date('Y-m-d',strtotime($purchase['receive_date'])) : null,
            'adminSN' => $this->_userSN
        ];

        if ($id == 0) {
            $insert['cdUserSN'] = $this->_loggedIn;
            $id = $this->Admin_Model->insertCommonTableWithoutSN($insert, 'purchases', true);
            if (false == (bool)$id) {
                return $this->response(array('response' => 'Database could not be updated..', 'code' => 400));
            }
        } else {
            $insert['mdUserSN'] = $this->_loggedIn;
            $insert['modifiedDate'] = date('Y-m-d H:i:s');
            $this->Admin_Model->updateTable('purchases', $insert, ['id' => $id]);
        }

        $insert = [
            'user_type' => isset($shipping['user_type']) ? $shipping['user_type'] : null,
            'name' => isset($shipping['name']) ? $shipping['name'] : null,
            'company' => isset($shipping['company']) ? $shipping['company'] : null,
            'address' => isset($shipping['address']) ? $shipping['address'] : null,
            'city' => isset($shipping['city']) ? $shipping['city'] : null,
            'postcode' => isset($shipping['postcode']) ? $shipping['postcode'] : 0,
            'region' => isset($shipping['region']) ? $shipping['region'] : null,
            'country' => isset($shipping['country']) ? $shipping['country'] : null,
            'instruction' => isset($shipping['instruction']) ? $shipping['instruction'] : null,
            'phone' => isset($shipping['phone']) ? $shipping['phone'] : null,
            'mobile' => isset($shipping['mobile']) ? $shipping['mobile'] : null,
            'email' => isset($shipping['email']) ? $shipping['email'] : null
        ];


        $insert['user_id'] = $insert['user_type'] == 'customer' ? $shipping['sn'] : $this->_loggedIn;

        if ($this->db->where(['purchase_id' => $id])->get('purchase_shipping')->num_rows() == 0) {
            $insert['purchase_id'] = $id;
            $this->Admin_Model->insertCommonTableWithoutSN($insert, 'purchase_shipping');
        } else {
            $this->Admin_Model->updateTable('purchase_shipping', $insert, ['purchase_id' => $id]);
        }

        $insert = [];
        foreach ($items as $item) {
            $temp = [
                'product_id' => isset($item['product_id']) ? $item['product_id'] : 0,
                'freight' => isset($item['freight']) ? $item['freight'] : 0,
                'price' => isset($item['price']) ? $item['price'] : 0,
                'qty' => isset($item['qty']) ? $item['qty'] : 0,
                'total' => isset($item['total']) ? $item['total'] : 0,
                'purchase_id' => $id
            ];
            $insert[] = $temp;
        }
        $this->Admin_Model->deleteCommonTable(['purchase_id' => $id], 'purchase_items');

        $this->Admin_Model->insertBatch('purchase_items', $insert);

        $this->response(['response' => 'Purchase has been saved successfully.', 'code' => 200, 'purchase' => $id]);
    }
}