<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pages extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->_init();
    }

    private function _init() {
        $this->output->set_template('exotic');
        $this->load->model('Admin_Model');
    }

    public function index($slug = 'index') {
        $this->load->set_current_nav($slug);
        $dataArr = array('slugName' => $slug);
        $pagesArr = $this->Admin_Model->getCommonTable($dataArr, 'pages');
        $data['pagesArr'] = $pagesArr[0];
        $data['slug'] = $slug;
        $dataArr = array('featured' => "Y");
        $selectArr = array('sn, name, image, days');
        $data['featuredArr'] = $this->Admin_Model->getCommonTable($dataArr, 'packages', '', '', $selectArr, 3);

        $this->output->set_common_meta($pagesArr[0]['metaTitle'], $pagesArr[0]['metaDesc'], $pagesArr[0]['metaKeyword']);
        $this->load->view('exotic/pages/index', $data);
    }

    public function info($slug = 'index') {
        $this->load->set_current_nav($slug);
        $dataArr = array('slugName' => $slug);
        $pagesArr = $this->Admin_Model->getCommonTable($dataArr, 'pages');
        $data['pagesArr'] = $pagesArr[0];
        $data['slug'] = $slug;
        if ($slug == "index") {
            $dataArr = array('featured' => "Y");
            $selectArr = array('sn, name, image, days');
            $data['featuredArr'] = $this->Admin_Model->getCommonTable($dataArr, 'packages', '', '', $selectArr, 3);
        }
        $this->output->set_common_meta($pagesArr[0]['metaTitle'], $pagesArr[0]['metaDesc'], $pagesArr[0]['metaKeyword']);
        $this->load->view('exotic/pages/index', $data);
    }

    public function enquiry($packageName = '', $packageSN = 0) {
        $this->load->js('assets/themes/exotic/js/jquery-validate.js');
        $data['packageName'] = $packageName;
        $data['packageSN'] = $packageSN;
        $this->load->view('exotic/pages/enquiry', $data);
    }

    public function enquiryNowSubmit() {
        $this->output->unset_template();
        if ($this->input->post()) {
            $dataArr = array(
                'packageName' => $this->input->post('packageName'),
                'packageSN' => $this->input->post('packageSN'),
                'name' => $this->input->post('firstName'),
                'email' => $this->input->post('email'),
                'phoneNo' => $this->input->post('phoneNo'),
                'comments' => $this->input->post('comments'),
                'sendEmail' => "N",
                'createdDate' => date("Y-m-d H:i:s")
            );
            $enquirySN = $this->Admin_Model->insertEnquiryTable($dataArr, 0, 'enquiries');
            $to = "info@exoticholidays.co.nz";
            //$to = "sgmalways4u@gmail.com";
            $subject = "Website Enquiry";
            $message = '<table border="1">'
                    . '<tr><td>Package Name</td><td>' . $this->input->post('packageName') . '</td></tr>'
                    . '<tr><td> Name</td><td>' . $this->input->post('firstName') . '</td></tr>'
                    . '<tr><td>Email</td><td>' . $this->input->post('email') . '</td></tr>'
                    . '<tr><td>Phone No</td><td>' . $this->input->post('phoneNo') . '</td></tr>'
                    . '<tr><td>Comments</td><td>' . $this->input->post('comments') . '</td></tr>'
                    . '</table>';
            $from = $this->input->post('email');
            $fromName = $this->input->post('firstName');
            $return = $this->Admin_Model->sendEmail1($from, $fromName, $to, '', '', $message, $subject);

            $dataArr = array(
                'sendEmail' => "Y"
            );
            $this->Admin_Model->saveCommonTable($dataArr, $enquirySN, 'enquiries');

            echo 'true';
            exit;
        }
    }

    public function brochures() {
        $this->load->set_current_nav('brochures');
        $data['brochuresArr'] = $this->Admin_Model->getCommonTable('', 'brochures');
        $this->load->view('exotic/pages/brochures', $data);
    }

    public function flyers() {
        $this->load->set_current_nav('flyers');
        $data['brochuresArr'] = $this->Admin_Model->getCommonTable('', 'flyers');
        $this->load->view('exotic/pages/flyers', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */