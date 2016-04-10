<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php 
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/third_party/imageresize/autoload.php';

class Products extends REST_Controller
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

    public function product_get()
    {
        $dataArr = array('table_name' => 'products');
        $get_save_search = $this->Admin_Model->getCommonTable($dataArr, 'save_search');

        if (!empty($get_save_search)) {
            $data_search = array();

            for ($i = 0; $i < count($get_save_search); $i++) {
                $dataArr = array('id' => $get_save_search[$i]['selected_id']);
                $data = $this->Admin_Model->getCommonTable($dataArr, 'products');
                array_push($data_search, $data[0]);
            }
            // $this->response($data_search, 200); // 200 being the HTTP response code
        } else {
            $data = $this->Admin_Model->getTable('products', $dataArr = ['adminSN' => $this->_userSN], $pk = 'id', 'P');
        }

        $product_price_data = $this->db->get_where('product_prices')->result_array();
        $product_photos_data = $this->db->get_where('product_photos')->result_array();

        $stock_data = $this->db->get_where('product_stock')->result_array();

        for ($i = 0; $i < count($data); $i++) {
            for ($j = 0; $j < count($product_price_data); $j++) {
                if ($data[$i]['id'] == $product_price_data[$j]['product_id']) {
                    $data[$i]['cost_inc_gst'] = $product_price_data[$j]['cost_inc_gst'];
                    $data[$i]['website_price'] = $product_price_data[$j]['website_price'];
                }
            }
        }

        for ($i = 0; $i < count($data); $i++) {
            for ($j = 0; $j < count($product_photos_data); $j++) {
                if ($data[$i]['id'] == $product_photos_data[$j]['product_id']) {
                    $data[$i]['image_name'] = $product_photos_data[$j]['name'];

                }
            }
        }

        if (false == (bool)$data) {
            echo json_encode([]);
        } else {
            end($data);
            $last_key = key($data);
            $last_value = array_pop($data);
            $data = array_merge(array($last_key => $last_value), $data);

            echo json_encode($data);
        }
    }

    public function edit_post()
    {
        $product = $this->post('product');
        //prepare product table
        if (!isset($product['product'])) {
            $this->response(array('result' => "Product is not defined.", 'code' => 400));
            return;
        }
        $pro = $product['product'];
        $pro['status'] = isset($pro['status']) ? $pro['status'] : 1;
        $pro['is_accessory'] = isset($pro['is_accessory']) ? (int)$pro['is_accessory'] : 0;

        if ($this->uri->segment(4) == 0) {
            $action = 'insert';
            $pro['cdUserSn'] = $this->_loggedIn;
            $pro['adminSN'] = $this->_loggedIn;
            $this->db->insert('products', $pro);
            $proId = $this->db->insert_id();
        } else {
            $action = 'update';
            $pro['modified_date'] = date('Y-m-d H:i:s');

            $this->db->where('id', $this->uri->segment(4))->update('products', $pro);
            $proId = $this->uri->segment(4);
        }

        if (!empty($proId)) {
            $values = array(
                'photo_id' => $proId
            );

            $this->db->where('id', $proId)->update('products', $values);
        }

        //product vendor table
        if (isset($product['vendors'])) {
            $shipping = $product['vendors'];
            if (is_array($shipping)) {
                // do nothig
            } else {
                if (!empty($shipping)) {
                    $vendor_value = array(
                        'vendor_name' => $shipping,
                    );
                    $this->db->where('id', $proId)->update('products', $vendor_value);
                }
            }
        }

        // product accesories table
        if (isset($product['accessories'])) {
            $shipping = $product['accessories'];
            if (!empty($shipping)) {
                $value = array();

                if ($action == 'update') {
                    $this->db->where('product_id', $proId)->delete('products_accessories');
                }

                foreach ($shipping as $cat) {
                    $value[] = array('product_id' => $proId, 'name' => $cat['name'], 'photo_id' => $cat['photo_id']);
                }

                $this->db->insert_batch('products_accessories', $value);

            } else {
                if (empty($shipping)) {
                    if ($action == 'update') {
                        $this->db->where('product_id', $proId)->delete('products_accessories');
                    }
                } else {
                    // do nothing
                }
            }
        }

        //product serial keys table
        if (isset($product['serial_keys'])) {
            $serial_keys_values = [];
            $shipping = $product['serial_keys'];

            if ($action == 'update') {
                $this->db->where('product_id', $proId)->delete('product_serial_keys');
            }

            if (!empty($shipping)) {
                foreach ($shipping as $cat) {
                    $serial_keys_values[] = array('product_id' => $proId, 'serial_key' => $cat);
                }
                if (count($serial_keys_values) > 0) {
                    $this->db->insert_batch('product_serial_keys', $serial_keys_values);
                }
            }
        }

        //pricespy
        if (isset($product['pricespy']) && !empty($product['pricespy']) && $proId > 0) {
            if (isset($product['pricespy']['lowest_rate']) &&
                isset($product['pricespy']['decrease_by']) &&
                isset($product['pricespy']['lowest_rate_dealer']) &&
                isset($product['pricespy']['url'])
            ) {
                $pricespy = [
                    'url' => @filter_var($product['pricespy']['url'], FILTER_SANITIZE_URL),
                    'lowest_rate' => @doubleval($product['pricespy']['lowest_rate']),
                    'decrease_by' => @doubleval($product['pricespy']['decrease_by']),
                    'product_id' => $proId
                ];
                if ($product['pricespy']['lowest_rate_dealer'] > 0) {
                    $pricespy['lowest_rate_dealer'] = $product['pricespy']['lowest_rate_dealer'];
                }

                if (isset($product['pricespy']['sno']) && $product['pricespy']['sno'] > 0) {
                    $this->db->where('sno', $product['pricespy']['sno'])->update('product_pricespy', $pricespy);
                    $this->db->where('pricespy_id', $product['pricespy']['sno'])->delete('pricespy_dealers');
                } else {
                    $this->db->insert('product_pricespy', $pricespy);
                    $product['pricespy']['sno'] = $this->db->insert_id();
                }

                if (isset($product['pricespy']['pricespy_dealers'])) {
                    $dealers = array_unique(array_filter(array_map('intval',
                        $product['pricespy']['pricespy_dealers'])));
                    if (!empty($dealers)) {
                        $insert = array();
                        foreach ($dealers as $d) {
                            $insert[] = [
                                'pricespy_id' => $product['pricespy']['sno'],
                                'dealer_id' => $d
                            ];
                        }
                        $this->db->insert_batch('pricespy_dealers', $insert);
                    }
                }
            }
        }

        //product stock
        if (isset($product['stock'])) {
            $stock = array();
            if (isset($product['stock']['available_to_stock'])) {
                $stock['available_to_stock'] = @boolval($product['stock']['available_to_stock']);
                unset($product['stock']['available_to_stock']);
            }

            $stock = array_map('intval', $product['stock']);
            if (!empty($stock)) {
                if ($action == 'insert') {
                    $stock['product_id'] = $proId;
                    $this->db->insert('product_stock', $stock);
                } elseif ($action == 'update') {

                    $this->db->where('product_id', $proId)->update('product_stock', $stock);
                }
            }
        }
        //product shipping table
        if (isset($product['shipping'])) {
            $shipping = array_map('doubleval', $product['shipping']);
            if ($shipping['id']) {
                unset($shipping['id']);
                unset($shipping['action']);
            }

            if (!empty($shipping)) {
                if ($action == 'insert') {
                    $shipping['product_id'] = $proId;

                    $this->db->insert('product_shipping', $shipping);
                } elseif ($action == 'update') {
                    $this->db->where('product_id', $proId)->update('product_shipping', $shipping);
                }
            }
        }

        //prepare product categories table
        if (isset($product['categories']) && count($product['categories']) > 0) {
            $cats = $product['categories'][2];
            $categories = array();
            if ($action == 'update') {
                $this->db->where('product_id', $proId)->delete('product_categories');
            }
            foreach ($cats as $cat) {
                $categories[] = array('product_id' => $proId, 'cat_id' => $cat['id'], 'name' => $cat['name']);
            }
            if (!empty($categories)) {
                $this->db->insert_batch('product_categories', $categories);
            }
        }
        //prepare product prices table

        if (isset($product['prices']) && $price = $product['prices']) {
            $prices = array();

            if (isset($price['buy_now'])) {
                $price['buy_now'] = boolval($price['buy_now']);
                unset($price['buy_now']);
            }

            if (isset($price['offer_buy_now'])) {
                $price['offer_buy_now'] = boolval($price['offer_buy_now']);
                unset($price['offer_buy_now']);
            }
            if (isset($price['use_last_purchse_order'])) {
                $price['use_last_purchse_order'] = boolval($price['use_last_purchse_order']);
                unset($price['use_last_purchse_order']);
            }
            $prices = array_merge($prices, array_map('doubleval', $price));
            $prices['product_id'] = $proId;
            if (!empty($prices)) {
                if ($action == 'insert') {
                    $this->db->insert('product_prices', $prices);
                } elseif ($action == 'update') {
                    $this->db->where('product_id', $proId)->delete('product_prices');
                    $this->db->insert('product_prices', $prices);
                }
            }
        }

        //product locations table        
        if (isset($product['location']) && $location = $product['location']) {
            $default = isset($location['default']) ? $location['default'] : 0;
            $shipping = $product['location'];

            if (!empty($shipping)) {
                $value = array();

                if ($action == 'update') {
                    $this->db->where('product_id', $proId)->delete('product_location');
                }

                foreach ($shipping as $cat) {
                    $value[] = array('product_id' => $proId, 'location_id' => $cat['id'], 'is_default' => 1);
                }

                $this->db->insert_batch('product_location', $value);

            } else {
                if (empty($shipping)) {
                    if ($action == 'update') {
                        $this->db->where('product_id', $proId)->delete('product_location');
                    }
                } else {
                    // do nothing
                }
            }
        }

        //product compatibles tables
        if (isset($product['compatibles'])) {
            $compatibles = $product['compatibles'];
            $comp = array();
            if ($action == 'update') {
                $this->db->where('product_id', $proId)->delete('product_compatibles');
            }
            for ($i = 0; $i < count($compatibles); $i++) {
                $comp[] = array('product_id' => $proId, 'device_id' => $compatibles[$i]);
                if (!empty($comp)) {
                    $this->db->insert_batch('product_compatibles', $comp);
                    $comp = array();
                }
            }

        }

        //product seo table
        if (isset($product['seo'])) {
            if (!empty($product['seo'])) {
                $product['seo']['table_name'] = "products";
                $product['seo']['table_id'] = $proId;
                if ($action == 'insert') {
                    $this->db->insert('seo', $product['seo']);
                } elseif ($action == 'update') {
                    $verifying = $this->db->where('table_id',
                        $product['seo']['table_id'])->select('table_id')->get('seo')->row();
                    if (empty($verifying)) {
                        $this->db->insert('seo', $product['seo']);
                    } else {
                        $this->db->where('table_id', $proId)->update('seo', $product['seo']);
                    }
                }
            }
        }

        //upload photos

        $this->response(array(
            'response' => 'Product has been ' . rtrim($action, 'e') . 'ed',
            'product' => $proId,
            'code' => 200
        ));
    }

    public function copy_post()
    {
        $productId = $this->post('productId');
        $copyId = $this->post('copyId');
        $product = $this->post('product');

        unset($product['sn']);
        $product['product_id'] = $productId;

        $this->db->insert('product_photos', $product);

        $path = ROOTPATH . '/uploads/products/' . $this->_userSN . '/' . $productId;
        $copyPath = ROOTPATH . '/uploads/products/' . $this->_userSN . '/' . $copyId . '/' . $product['name'];
        if (!file_exists(ROOTPATH . '/' . $path)) {
            @mkdir($path, 0755, true);
        }

        copy($copyPath, $path . '/' . $product['name']);
    }

    public function updateSelected_post()
    {
        $for = $this->post('for');
        $status = $this->post('status');
        $rows = $this->post('rows');
        if ($status == 2) {
            $status = 0;
        }

        if (!empty($rows)) {
            for ($i = 0; $i < count($rows); $i++) {
                $id = $rows[$i]['id'];
                $pro[$for] = $status;
                $this->db->where('id', $id)->update('products', $pro);
            }
            return $this->response(array('response' => 'Update selected succesfully', 'code' => 200));
        } else {
            return $this->response(array('code' => 404));
        }
    }

    public function updateSelectedPrice_post()
    {
        $data = $this->post('data');

        if (!empty($data)) {
            for ($i = 0; $i < count($data); $i++) {
                $id = $data[$i]['sno'];
                unset($data[$i]['sno']);
                unset($data[$i]['name']);
                $this->db->where('sno', $id)->update('product_prices', $data[$i]);
            }
            return $this->response(array(
                'response' => 'Prices of selected products were updated succesfully',
                'code' => 200
            ));
        } else {
            return $this->response(array('code' => 404));
        }
    }

    public function import_location_post()
    {
        $importLocation = $this->post('importLocation');
        $insert = false;
        for ($i = 0; $i < count($importLocation); $i++) {
            $query = [
                'code' => $importLocation[$i]['cells'][0]['value'],
                'description' => $importLocation[$i]['cells'][1]['value'],
                'warehouseSN' => $importLocation[$i]['cells'][2]['value'],
                'adminSN' => $this->_loggedIn,
                'cdUserSN' => $this->_loggedIn,
                'createdDate' => date('Y-m-d H:i:s')
            ];

            if ($this->db->insert('products_locations', $query)) {
                $insert = true;
            } else {
                $insert = false;
            }
        }
        if ($insert == true) {
            $this->response(array('response' => 'success', 'code' => 200));
        }

    }

    public function import_product_post()
    {
        $importProduct = $this->post('importProduct');

        for ($i = 0; $i < count($importProduct); $i++) {

            $query = [
                'name' => $importProduct[$i]['cells'][1]['value'],
                'short_description' => $importProduct[$i]['cells'][6]['value'],
                'adminSN' => $this->_loggedIn,
                'cdUserSN' => $this->_loggedIn
            ];


            $this->db->insert('products', $query);
            $proId = $this->db->insert_id();

            $query2 = [
                'cost_inc_gst' => $importProduct[$i]['cells'][4]['value'],
                'store_price' => $importProduct[$i]['cells'][3]['value'],
                'product_id' => $proId
            ];
            $this->db->insert('product_prices', $query2);
        }
        $this->response(array('response' => 'Product has been import', 'product' => $proId, 'code' => 200));
    }

    public function rest_post()
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
                    $this->db->where($key, $where);
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
        $this->response(array('result' => $result, 'code' => 200, 'user' => $this->_userSN));
    }


    public function productMulti_post()
    {
        $id = $this->post('id');

        $ids = implode(',', $id);

        if ($this->db->query("delete from products where id in ($ids)")) {
            echo json_encode(array('response' => 'Selected products were deleted.', 'status' => 'success'));
        } else {
            echo json_encode(array('response' => 'Selected products could not be deleted.', 'status' => 'error'));
        }
    }

    public function upload_post()
    {
        $product = $this->post('product');

        if (!isset($_FILES['file'])) {
            $query = [
                'description' => $this->post('description'),
                'is_default' => $this->post('is_default'),
                'is_auction' => $this->post('is_auction'),
                'is_website' => $this->post('is_website'),
                'product_id' => $product
            ];

            $this->db->where('sn', $this->post('sn'))->update('product_photos', $query);
            return $this->response(array("response" => "File not selected.", 'status' => 400));
        }

        $pro = $this->db->where('id', $product)->select('name')->get('products')->row();

        if (false == $pro) {
            return $this->response(array("response" => "Product does not exist.", 'status' => 400));
        }

        $path = ROOTPATH . '/uploads/products/' . $this->_userSN . '/' . $product;

        if (!file_exists(ROOTPATH . '/' . $path)) {
            @mkdir($path, 0755, true);
        }
        $filename = str_replace(' ', '-', $pro->name) . '-' . $product . '.' . pathinfo($_FILES['file']['name'],
                PATHINFO_EXTENSION);
        $config = [];
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = $filename;
        $config['overwrite'] = false;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $this->response(array('response' => $this->upload->display_errors(), 'status' => 400));
        } else {
            $query = [
                'name' => $this->upload->data()['file_name'],
                'description' => $this->post('description'),
                'is_default' => $this->post('is_default'),
                'is_auction' => $this->post('is_auction'),
                'is_website' => $this->post('is_website'),
                'product_id' => $product
            ];

            $image = new \Eventviva\ImageResize($path . '/' . $filename);
            $image->
            resizeToWidth(600)->
            save($path . '/' . $this->config->item('img_prefix_large') . $filename)->
            resizeToWidth(300)->
            save($path . '/' . $this->config->item('img_prefix_medium') . $filename)->
            resizeToWidth(150)->
            save($path . '/' . $this->config->item('img_prefix_small') . $filename);

            if ($this->post('sn') == 0) {
                $this->db->insert('product_photos', $query);
            } else {
                $this->db->where('sn', $this->post('sn'))->update('product_photos', $query);
            }

            $this->response(array('response' => $this->upload->data(), 'status' => 200));
        }
    }
}
