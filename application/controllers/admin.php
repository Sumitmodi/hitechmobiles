<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends MY_Controller
{

    private $_loggedIn = 0;
    private $_ParentLoggedIn = 0;

    function __construct()
    {
        parent::__construct();
        $this->_init();
    }

    private function _init()
    {
        $this->output->set_template('default');
        // $this->load->model('Admin_Model');
        $this->load->library('form_validation');
        $this->load->library('ckeditor');
        if (!$this->session->userdata('logged_hitech')) {
            redirect('/login/admin');
        }
        date_default_timezone_set('Pacific/Auckland');
        $this->_loggedIn = $this->session->userdata('logged_hitech_admin_id');
        $this->_ParentLoggedIn = $this->session->userdata('logged_hitech_adminParentSN');
    }

    public function index()
    {

    }

    public function profile($action = '')
    {
        $this->load->set_current_nav('setup');
        $data['status'] = '';

        if ($this->input->post()) {
            $data['status'] = 'Saved';
            $filename = '';

            if ($action == 'users') {
                $dataArr = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'status' => $this->input->post('status'),
                    'parentSn' => $this->_loggedIn,
                );
                $sn = $this->input->post('sn');
                if ($sn == 0) {
                    $dataArr['createdDate'] = date("Y-m-d H:i:s");
                }
            } else {
                $dataArr = array(
                    'name' => $this->input->post('name'),
                );
                if (!empty($_FILES['logo']['name'])) {
                    $uploadPath = $this->config->item('profile_upload_path');
                    $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'logo', "500", "5000", "5000");
                    $filename = $return['upload_data']['file_name'];
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "600", "600",
                        $uploadPath . "m_" . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "150", "150",
                        $uploadPath . "t_" . $filename);
                    $dataArr['logo'] = $filename;
                }
            }
            if ($this->input->post('password')) {
                $dataArr['password'] = md5($this->input->post('password'));
            }

            if ($action == 'users') {
                $this->Admin_Model->saveCommonTable($dataArr, $sn, 'admin_login');
            } else {
                $this->Admin_Model->saveCommonTable($dataArr, $this->_loggedIn, 'admin_login');
            }
        }
        if ($action == 'users') {
            $dataArr = array('parentSN' => $this->_loggedIn);
            $data['resultArr'] = $this->Admin_Model->getCommonTable($dataArr, 'admin_login');
            $this->load->view('admin/setup/allusers', $data);
        } else {
            $dataArr = array('sn' => $this->_loggedIn);
            $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'admin_login');
            $data['resultArr'] = $resultArr[0];
            $this->load->view('admin/setup/profile', $data);
        }
    }

    public function testimonials($action = '', $sn = 0)
    {
        $data = array();
        if ($action == "edit") {
            $data['resultArr'] = array();
            if ($sn > 0) {
                $data['resultArr'] = array();
                $data['sn'] = $sn;
            } else {
                $data['sn'] = 0;
            }
            $this->load->view('admin/testimonials/edit', $data);
        } else {
            if ($action == "delete") {

            } else {
                $data['resultArr'] = array();
                $this->load->view('admin/testimonials/index', $data);
            }
        }
    }

    public function customers($action = '', $sn = 0)
    {
        if ($action == "edit") {
            $data['resultArr'] = array();
            if ($sn > 0) {
                $data['resultArr'] = array();
                $data['attachmentArr'] = array();
                $data['emailArr'] = array();
                $data['sn'] = $sn;
            } else {
                $data['sn'] = $sn;
            }
            $this->load->view('admin/customers/edit', $data);
        } else {
            if ($action == "delete") {

            } else {

                $data['resultArr'] = array();
                $data['title'] = 'Customers list';
                $this->load->view('admin/customers/index', $data);
            }
        }
    }

    public function products($action = '', $sn = 0)
    {
        $data = array('title' => 'Products');
        if ($action == "edit") {
            $data['sn'] = $sn;
            $cats = $this->db->select('name,id,parent_id')->where('LOWER(status)',
                'y')->order_by('name asc')->get('category')->result_array();

            $vendors = $this->db->select('name,id')->where('LOWER(status)',
                'y')->order_by('name asc')->get('vendors')->result_array();

            $warehouse = $this->db->select('name,sn')->where('LOWER(status)',
                'y')->order_by('name asc')->get('warehouse')->result_array();

            $product = $this->db->where('id', $sn)->get('products')->row();

            $product_accessories = $this->db->where('product_id', $sn)->get('products_accessories')->result_array();

            $product_categories = $this->db->where('product_id', $sn)->get('product_categories')->result_array();

            $data['cats'] = $cats;
            $data['warehouse'] = $warehouse;
            $data['vendors'] = $vendors;
            $data['product'] = $product;
            $data['product_accessories'] = $product_accessories;
            $data['product_categories'] = $product_categories;

            $this->load->view('admin/products/edit', $data);
        } else {
            if ($action == "delete") {

            } else {
                if ($action == "copy") {
                    $data['sn'] = $sn;
                    $this->load->view('admin/products/edit', $data);
                } else {
                    if ($action == "import") {
                        $this->load->view('admin/products/import');
                    } else {
                        $this->load->view('admin/products/index', $data);
                    }
                }
            }
        }


    }

    // public function pages($action = '', $sn = 0)
    // {
    //     $data = array('title' => 'Pages');
    //     if ($action == "edit")
    //     {
    //         $data['sn'] = $sn;
    //         $cats = $this->db->select('name,id,parent_id')->where('LOWER(status)', 'y')->order_by('name asc')->get('category')->result_array();

    //         $vendors = $this->db->select('name,id')->where('LOWER(status)', 'y')->order_by('name asc')->get('vendors')->result_array();

    //         $warehouse = $this->db->select('name,sn')->where('LOWER(status)', 'y')->order_by('name asc')->get('warehouse')->result_array();

    //         $product = $this->db->where('id', $sn)->get('products')->row();

    //         $product_accessories = $this->db->where('product_id', $sn)->get('products_accessories')->result_array();

    //         $product_categories = $this->db->where('product_id', $sn)->get('product_categories')->result_array();

    //         $data['cats'] = $cats;
    //         $data['warehouse'] = $warehouse;
    //         $data['vendors'] = $vendors;
    //         $data['product'] = $product;
    //         $data['product_accessories'] = $product_accessories;
    //         $data['product_categories'] = $product_categories;

    //         $this->load->view('admin/products/edit', $data);
    //     } else if ($action == "delete")
    //     {

    //     } else
    //     {
    //         $this->load->view('admin/pages/pages/index', $data);
    //     }
    // }

    public function products_locations($action = '', $sn = 0)
    {
        if ($action == "edit") {
            $data['resultArr'] = array();
            $data['sn'] = $sn;
            $this->load->view('admin/products/locations/edit', $data);
        } else {
            if ($action == "delete") {

            } else {
                if ($action == "import") {
                    $this->load->view('admin/products/locations/import');
                } else {
                    $data['resultArr'] = array();
                    $this->load->view('admin/products/locations/index', $data);
                }
            }
        }
    }

    public function warehouse()
    {
        $this->load->view('admin/products/warehouse/index');
    }

    public function category()
    {
        $this->load->view('admin/products/category/index');
    }

    public function compatibles()
    {
        $this->load->view('admin/products/compatibles/index');
    }

    public function vendor()
    {
        $this->load->view('admin/products/vendor/index');
    }

    public function payment_type()
    {
        $this->load->view('admin/order/payment_type/index');
    }

    public function shipping_type()
    {
        $this->load->view('admin/order/shipping_type/index');
    }

    public function country($action = '', $sn = 0)
    {
        if ($action == "edit") {
            $this->load->view('admin/utility/country/edit');
        } else {
            $this->load->view('admin/utility/country/index');
        }
    }

    public function order($action = '', $sn = 0, $order_id = 0)
    {
        if ($action == "edit") {
            $this->load->view('admin/order/order/edit');
        } else {
            if ($action == "orderDetail") {
                $data['id'] = $sn;
                if (empty($order_id)) {
                    $this->load->view('admin/order/order/orderDetail', $data);
                } else {
                    $data['order_id'] = $order_id;
                    $this->load->view('admin/order/order/orderNotes', $data);
                }
            } else {
                $this->load->view('admin/order/order/index');
            }
        }
    }


    public function jobs($action = '', $sn = 0, $customer_id = 0)
    {
        if ($action == 'edit') {
            // if ($sn>0) {
            //     $data['id']=$sn;
            //     $this->load->view('admin/jobs/dashboard/comments',$data);
            // } else {
            $this->load->view('admin/jobs/dashboard/edit');
            // }
        } else {
            if ($action == "comments") {
                $data['id'] = $sn;

                if ($sn > 0 && $customer_id == 0) {
                    $this->load->view('admin/jobs/dashboard/commentsDashboard', $data);
                } else {
                    $data['customer_id'] = $customer_id;
                    $this->load->view('admin/jobs/dashboard/comments', $data);
                }
            } elseif ($action == "deviceType") {
                if ($customer_id == 1) {
                    $this->load->view('admin/jobs/device_type/edit');
                } else {
                    $this->load->view('admin/jobs/device_type/index');
                }

            } else {
                if ($action == "copy") {
                    $this->load->view('admin/jobs/dashboard/edit');
                } else {
                    $this->load->view('admin/jobs/dashboard/index');
                }
            }
        }
    }

    public function keep_omit_reset($identify)
    {
        $dataArr = array(
            'userSN' => $this->_loggedIn,
            'identify' => $identify
        );
        $this->Admin_Model->deleteCommonTable($dataArr, 'keep_omit');
    }

    public function readCSV()
    {
        $row = 1;
        if (($handle = fopen($this->config->item('csv_upload_path') . "csv_export.csv", "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $num = count($data);
                //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    // echo $data[$c] . "<br />\n";
                }
                $dataArr = array(
                    'sn' => $data[0],
                    'mailingList' => ($data[1] == 0 ? "N" : "Y"),
                    'feedbackEnabled' => ($data[2] == 0 ? "N" : "Y"),
                    'name' => $data[3],
                    'anAccount' => ($data[4] == 0 ? "N" : "Y"),
                    'companyName' => $data[5],
                    'email' => $data[6],
                    'phone' => $data[8],
                    'mobile' => $data[9],
                    'city' => $data[10],
                    'address' => $data[11],
                    'postcode' => $data[12]
                );

                $this->Admin_Model->insertCommonTableWithoutSN($dataArr, 'customers');
            }
            fclose($handle);
        }
    }

    public function keep_omit($identify, $flag, $selectedId)
    {
        $selectArr = explode(":", $selectedId);
        $this->keep_omit_reset($identify);
        foreach ($selectArr as $selectSN) {
            if ($selectSN > 0) {

                $dataArr = array(
                    'userSN' => $this->_loggedIn,
                    'identify' => $identify,
                    'flag' => $flag,
                    'selectSN' => $selectSN
                );
                $this->Admin_Model->insertCommonTable($dataArr, 0, 'keep_omit');
            }
        }
    }

    public function destinations($action = 'view', $sn = 0, $status = '')
    {
        exit;
        $this->load->set_current_nav('destinations');
        $data['status'] = $status;
        if ($action == "edit") {
            $this->ckeditor->basePath = $this->config->item('theme_admin_url') . 'ckeditor/';
            $this->ckeditor->config['width'] = '100%';
            $this->ckeditor->config['height'] = '300px';
            if ($this->input->post()) {
                $data['status'] = 'Saved';
                $filename = '';


                if (!empty($_FILES['image']['name'])) {
//UPload
                    $uploadPath = $this->config->item('destinations_upload_path');
                    $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'image', "500", "5000", "5000");
                    $filename = $return['upload_data']['file_name'];
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "1100", "1000", $uploadPath . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "600", "600",
                        $uploadPath . "m_" . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "150", "150",
                        $uploadPath . "t_" . $filename);
                }
                $dataArr = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'metaTitle' => $this->input->post('metaTitle'),
                    'metaDesc' => $this->input->post('metaDesc'),
                    'metaKeyword' => $this->input->post('metaKeyword'),
                    'status' => $this->input->post('status')
                );
                if (!empty($filename)) {
                    $dataArr['image'] = $filename;
                }
                if ($sn == 0) {
                    $dataArr['createdDate'] = date("Y-m-d H:i:s");
                }
                $this->Admin_Model->saveCommonTable($dataArr, $sn, 'destinations');
            }
            if ($sn > 0) {
                $dataArr = array('sn' => $sn);
                $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'destinations');
                if (empty($resultArr)) {
                    $data['resultArr'] = array();
                    $data['status'] = 'Sorry No Record';
                } else {
                    $data['resultArr'] = $resultArr[0];
                }
            } else {
                $data['resultArr'] = array();
            }
            if ($this->input->post() && $sn == 0) {
                redirect(base_url() . 'admin/destinations/view/0/Saved');
            }
            $this->load->view('admin/destinations/edit', $data);
        } else {
            if ($action == "delete" && $sn > 0) {
                $dataArr = array('sn' => $sn);
                $this->Admin_Model->deleteCommonTable($dataArr, 'destinations');
                redirect(base_url() . '/admin/destinations/view/0/Deleted');
            } else {
                $data['resultArr'] = $this->Admin_Model->getCommonTable('', 'destinations');
                $this->load->view('admin/destinations/index', $data);
            }
        }
    }

    public function packages($action = 'view', $sn = 0, $status = '')
    {
        $this->load->set_current_nav('packages');
        $data['status'] = $status;
        if ($action == "edit") {
            $this->ckeditor->basePath = $this->config->item('theme_admin_url') . 'ckeditor/';
            $this->ckeditor->config['width'] = '100%';
            $this->ckeditor->config['height'] = '300px';
            if ($this->input->post()) {
                $data['status'] = 'Saved';
                $filename = '';


                if (!empty($_FILES['image']['name'])) {
//UPload
                    $uploadPath = $this->config->item('packages_upload_path');
//echo $uploadPath;
                    $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'image', "500", "5000", "5000");
                    $filename = $return['upload_data']['file_name'];
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "1100", "1000", $uploadPath . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "600", "600",
                        $uploadPath . "m_" . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "150", "150",
                        $uploadPath . "t_" . $filename);
                }
                $days = (strlen(trim($this->input->post('days'))) > 0 ? $this->input->post('days') : "0");
                $dataArr = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'price' => $this->input->post('price'),
                    'shortIntro' => $this->input->post('shortIntro'),
                    'days' => $days,
                    'metaTitle' => $this->input->post('metaTitle'),
                    'metaDesc' => $this->input->post('metaDesc'),
                    'metaKeyword' => $this->input->post('metaKeyword'),
                    'featured' => $this->input->post('featured'),
                    'destinationsSN' => $this->input->post('destinationsSN'),
                    'status' => $this->input->post('status')
                );
                if (!empty($filename)) {
                    $dataArr['image'] = $filename;
                }
                if ($sn == 0) {
                    $dataArr['createdDate'] = date("Y-m-d H:i:s");
                }
                $this->Admin_Model->saveCommonTable($dataArr, $sn, 'packages');
            }
            if ($sn > 0) {
                $dataArr = array('sn' => $sn);
                $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'packages');
                if (empty($resultArr)) {
                    $data['resultArr'] = array();
                    $data['status'] = 'Sorry No Record';
                } else {
                    $data['resultArr'] = $resultArr[0];
                }
            } else {
                $data['resultArr'] = array();
            }
            if ($this->input->post() && $sn == 0) {
                redirect(base_url() . 'admin/packages/view/0/Saved');
            }
            $dataArr = array('status' => 'Y');
            $data['destinationsArr'] = $this->Admin_Model->getCommonTable($dataArr, 'destinations');
            $this->load->view('admin/packages/edit', $data);
        } else {
            if ($action == "delete" && $sn > 0) {
                $dataArr = array('sn' => $sn);
                $this->Admin_Model->deleteCommonTable($dataArr, 'packages');
                redirect(base_url() . '/admin/packages/view/0/Deleted');
            } else {
                $data['resultArr'] = $this->Admin_Model->getCommonTable('', 'packages');
                $this->load->view('admin/packages/index', $data);
            }
        }
    }

    public function pages($action = '', $sn = 0)
    {
        $data = array('title' => 'Pages');

        if ($action == "edit") {
            $this->load->view('admin/pages/pages/edit', $data);

        } else {
            $this->load->view('admin/pages/pages/index', $data);
        }
    }

    public function purchases($action = '', $sn = 0)
    {
        $data = array('title' => 'Purchases');
        $data['sn'] = $sn;

        if ($action == "edit") {
            $this->load->view('admin/purchases/edit', $data);

        } else {
            $this->load->view('admin/purchases/index', $data);
        }
    }

    public function dealers($action = '', $sn = 0)
    {
        $data = array('title' => 'Delares');
        if ($action == "edit") {
            $this->load->view('admin/dealers/dealers/edit', $data);

        } else {
            $this->load->view('admin/dealers/dealers/index', $data);
        }
    }

    public function slider($action = '', $sn = 0)
    {
        if ($action == "edit") {
            $this->load->view('admin/slider/edit');

        } else {
            $this->load->view('admin/slider/index');
        }
    }

    public function brochures($action = 'view', $sn = 0, $status = '')
    {
        $this->load->set_current_nav('brochures');
        $data['status'] = $status;
        if ($action == "edit") {
            $this->ckeditor->basePath = $this->config->item('theme_admin_url') . 'ckeditor/';
            $this->ckeditor->config['width'] = '100%';
            $this->ckeditor->config['height'] = '300px';
            if ($this->input->post()) {
                $data['status'] = 'Saved';
                $filename = '';


                if (!empty($_FILES['image']['name'])) {
                    //UPload
                    $uploadPath = $this->config->item('brochures_upload_path');
                    $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'image', "500", "5000", "5000");
                    $filename = $return['upload_data']['file_name'];
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "1100", "1000", $uploadPath . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "600", "600",
                        $uploadPath . "m_" . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "150", "150",
                        $uploadPath . "t_" . $filename);
                }
                $pdf = "";
                if (!empty($_FILES['pdf']['name'])) {
                    //UPload
                    $uploadPath = $this->config->item('brochures_upload_path');
                    $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'pdf', "0", "", "");
                    $pdf = $return['upload_data']['file_name'];
                }
                $dataArr = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'metaTitle' => $this->input->post('metaTitle'),
                    'metaDesc' => $this->input->post('metaDesc'),
                    'metaKeyword' => $this->input->post('metaKeyword'),
                    'status' => $this->input->post('status')
                );
                if (!empty($pdf)) {
                    $dataArr['pdf'] = $pdf;
                }
                if (!empty($filename)) {
                    $dataArr['image'] = $filename;
                }
                if ($sn == 0) {
                    $dataArr['createdDate'] = date("Y-m-d H:i:s");
                }
                $this->Admin_Model->saveCommonTable($dataArr, $sn, 'brochures');
            }
            if ($sn > 0) {
                $dataArr = array('sn' => $sn);
                $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'brochures');
                if (empty($resultArr)) {
                    $data['resultArr'] = array();
                    $data['status'] = 'Sorry No Record';
                } else {
                    $data['resultArr'] = $resultArr[0];
                }
            } else {
                $data['resultArr'] = array();
            }
            if ($this->input->post() && $sn == 0) {
                redirect(base_url() . 'admin/brochures/view/0/Saved');
            }
            $this->load->view('admin/brochures/edit', $data);
        } else {
            if ($action == "delete" && $sn > 0) {
                $dataArr = array('sn' => $sn);
                $this->Admin_Model->deleteCommonTable($dataArr, 'brochures');
                redirect(base_url() . '/admin/brochures/view/0/Deleted');
            } else {
                $data['resultArr'] = $this->Admin_Model->getCommonTable('', 'brochures');
                $this->load->view('admin/brochures/index', $data);
            }
        }
    }

    public function flyers($action = 'view', $sn = 0, $status = '')
    {
        $this->load->set_current_nav('flyers');
        $data['status'] = $status;
        if ($action == "edit") {
            $this->ckeditor->basePath = $this->config->item('theme_admin_url') . 'ckeditor/';
            $this->ckeditor->config['width'] = '100%';
            $this->ckeditor->config['height'] = '300px';
            if ($this->input->post()) {
                $data['status'] = 'Saved';
                $filename = '';


                if (!empty($_FILES['image']['name'])) {
//UPload
                    $uploadPath = $this->config->item('flyers_upload_path');
                    $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'image', "500", "5000", "5000");
                    $filename = $return['upload_data']['file_name'];
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "1100", "1000", $uploadPath . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "600", "600",
                        $uploadPath . "m_" . $filename);
                    $this->Admin_Model->resizingImage($filename, $uploadPath, "150", "150",
                        $uploadPath . "t_" . $filename);
                }
                $pdf = "";
                if (!empty($_FILES['pdf']['name'])) {
//UPload
                    $uploadPath = $this->config->item('flyers_upload_path');
                    $return = $this->Admin_Model->uploadImages($_FILES, $uploadPath, 'pdf', "0", "", "");
                    $pdf = $return['upload_data']['file_name'];
                }
                $dataArr = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'metaTitle' => $this->input->post('metaTitle'),
                    'metaDesc' => $this->input->post('metaDesc'),
                    'metaKeyword' => $this->input->post('metaKeyword'),
                    'status' => $this->input->post('status')
                );
                if (!empty($pdf)) {
                    $dataArr['pdf'] = $pdf;
                }
                if (!empty($filename)) {
                    $dataArr['image'] = $filename;
                }
                if ($sn == 0) {
                    $dataArr['createdDate'] = date("Y-m-d H:i:s");
                }
                $this->Admin_Model->saveCommonTable($dataArr, $sn, 'flyers');
            }
            if ($sn > 0) {
                $dataArr = array('sn' => $sn);
                $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'flyers');
                if (empty($resultArr)) {
                    $data['resultArr'] = array();
                    $data['status'] = 'Sorry No Record';
                } else {
                    $data['resultArr'] = $resultArr[0];
                }
            } else {
                $data['resultArr'] = array();
            }
            if ($this->input->post() && $sn == 0) {
                redirect(base_url() . 'admin/flyers/view/0/Saved');
            }
            $this->load->view('admin/flyers/edit', $data);
        } else {
            if ($action == "delete" && $sn > 0) {
                $dataArr = array('sn' => $sn);
                $this->Admin_Model->deleteCommonTable($dataArr, 'flyers');
                redirect(base_url() . '/admin/flyers/view/0/Deleted');
            } else {
                $data['resultArr'] = $this->Admin_Model->getCommonTable('', 'flyers');
                $this->load->view('admin/flyers/index', $data);
            }
        }
    }

    public function enquiries($action = 'view', $sn = 0, $status = '')
    {
        $this->load->set_current_nav('enquiries');
        $data['status'] = $status;
        if ($action == "edit") {
            if ($sn > 0) {
                $dataArr = array('sn' => $sn);
                $resultArr = $this->Admin_Model->getCommonTable($dataArr, 'enquiries');
                if (empty($resultArr)) {
                    $data['resultArr'] = array();
                    $data['status'] = 'Sorry No Record';
                } else {
                    $data['resultArr'] = $resultArr[0];
                }
            } else {
                $data['resultArr'] = array();
            }
            if ($this->input->post() && $sn == 0) {
                redirect(base_url() . 'admin/enquiries/view/0/Saved');
            }
            $this->load->view('admin/enquiries/edit', $data);
        } else {
            $data['resultArr'] = $this->Admin_Model->getCommonTable('', 'enquiries', 'sn', 'desc');
            $this->load->view('admin/enquiries/index', $data);
        }
    }

    public function updateSelected($action)
    {
        $data = $this->input->get('products');
        if (false == $data) {
            show_error("Products not selected.", 400);
        }
        $ids = array_unique(array_filter(explode(',', $data)));

        if ($action == "prices") {
            $product_data = $this->
            db->
            join('product_prices', 'products.id = product_prices.product_id', 'inner')->
            select('products.name,product_prices.*')->
            where_in('product_id', $ids)->
            get('products')->
            result_array();

            $product['product_data'] = $product_data;
            $this->load->view('admin/products/bulk_price_update', $product);
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_exotic');
        $this->session->unset_userdata('logged_exotic_admin_id');
        $this->session->unset_userdata('logged_exotic_admin_name');
        redirect(base_url() . 'login/admin');
    }

}
