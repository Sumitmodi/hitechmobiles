<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Destination extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->_init();
    }

    private function _init() {
        $this->output->set_template('exotic');
        $this->load->model('Admin_Model');
    }

    public function packages($destinationName = '', $destinationSN = 0) {
        $this->load->set_current_nav('destination');
        if ($destinationSN > 0) {
            $dataArr = array('sn' => $destinationSN);
            $selectArr = array('sn, name, metaTitle, metaDesc, metaKeyword');
            $destinationArr = $this->Admin_Model->getCommonTable($dataArr, 'destinations', '', '', $selectArr);
            if (!empty($destinationArr)) {
                $data['destination'] = $destinationArr[0];
                $this->output->set_common_meta($destinationArr[0]['metaTitle'], $destinationArr[0]['metaDesc'], $destinationArr[0]['metaKeyword']);
                $dataArr = array('destinationsSN' => $destinationSN);
                $selectArr = array('sn, name, image, days, featured');
                $data['packagesArr'] = $this->Admin_Model->getCommonTable($dataArr, 'packages', 'name', 'asc', $selectArr);
            } else {
                $data['destination'] = array();
                $data['packagesArr'] = array();
            }
            $this->load->view('exotic/destinations/packages', $data);
        }
    }

    public function packages_detail($packageName = '', $packageSN = 0) {
        $this->load->set_current_nav('destination');
        if ($packageSN > 0) {
            $dataArr = array('sn' => $packageSN);
            $packageArr = $this->Admin_Model->getCommonTable($dataArr, 'packages');
            if (!empty($packageArr)) {
                $data['package'] = $packageArr[0];
                $this->output->set_common_meta($packageArr[0]['metaTitle'], $packageArr[0]['metaDesc'], $packageArr[0]['metaKeyword']);
            } else {
                $data['package'] = array();
            }
            $this->load->view('exotic/destinations/package_detail', $data);
        }
    }

    public function info($destinationName = '', $destinationSN = 0) {
        $this->load->set_current_nav('destinationInfo');
        if ($destinationSN > 0) {
            $dataArr = array('sn' => $destinationSN);
            $destinationArr = $this->Admin_Model->getCommonTable($dataArr, 'destinations');
            if (!empty($destinationArr)) {
                $data['destination'] = $destinationArr[0];
                $this->output->set_common_meta($destinationArr[0]['metaTitle'], $destinationArr[0]['metaDesc'], $destinationArr[0]['metaKeyword']);
            } else {
                $data['destination'] = array();
            }
            $this->load->view('exotic/destinations/info', $data);
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */