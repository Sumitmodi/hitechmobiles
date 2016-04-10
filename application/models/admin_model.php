<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin_Model extends CI_Model
{

    function Admin_Model()
    {
        parent::__construct();
    }

    function check_login($email, $password)
    {
        $md5password = md5($password);
        $query_str = "select * from admin_login where email=? and password=?";
        $result = $this->db->query($query_str, array($email, $md5password));
        if ($result->num_rows() > 0) {
            return $result->row(0);
        } else {
            return false;
        }
    }

    public function getCommonTable(
        $dataArr = array(),
        $tableName,
        $orderByPara1 = '',
        $orderByPara2 = '',
        $selectArr = array(),
        $limit = ''
    ) {
        if (!empty($selectArr)) {
            $this->db->select(implode(",", $selectArr));
        }
        if (!empty($orderByPara1) && !empty($orderByPara2)) {
            $this->db->order_by($orderByPara1, $orderByPara2);
        }
        if (!empty($dataArr)) {
            if (!empty($limit)) {
                $result = $this->db->get_where($tableName, $dataArr, $limit);
            } else {
                $result = $this->db->get_where($tableName, $dataArr);
            }
        } else {
            if (!empty($limit)) {
                $result = $this->db->get($tableName, $limit);
            } else {
                $result = $this->db->get($tableName);
            }
        }
        return $result->result_array();
    }

    // Save Common Table
    function saveCommonTable($dataArr = array(), $sn, $tablename)
    {
        if ($sn == 0) {            // A record does not exist, insert one.            	
            $dataArr['sn'] = $sn;
            $this->db->set($dataArr, false);
            $query = $this->db->insert($tablename);
        } else {
            // A record does exist, update it.            
            $query = $this->db->update($tablename, $dataArr, array('sn' => $sn));
        }        // Check to see if the query actually performed correctly        
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    //Update commonTable 
    function updateCommonTable($dataArr = array(), $sn, $tablename)
    {
        if ($sn > 0) {
            $query = $this->db->update($tablename, $dataArr, array('sn' => $sn));
            return true;
        } else {
            return false;
        }
    }

    function updateTable($table, $data = array(), $where = array())
    {
        return $this->db->where($where)->update($table, $data);
    }

    function insertCommonTable_GetSN($dataArr = array(), $sn, $tablename)
    {
        // A record does not exist, insert one.
        $dataArr['sn'] = $sn;
        $this->db->set($dataArr, false);
        $this->db->insert($tablename);
        return $this->db->insert_id();
    }

    function insertCommonTable($dataArr = array(), $sn, $tablename)
    {
        // A record does not exist, insert one.
        $dataArr['sn'] = $sn;
        $this->db->set($dataArr, false);
        $this->db->insert($tablename);
        return true;
    }

    function insertCommonTableWithoutSN($dataArr = array(), $tablename, $return_id = false)
    {
        // A record does not exist, insert one.
        $this->db->set($dataArr, false);
        $this->db->insert($tablename);
        return $return_id == false ? true : $this->db->insert_id();
    }

    function insertBatch($table, $insert)
    {
        return $this->db->insert_batch($table, $insert);
    }

    //Delete Data from Table 
    function deleteCommonTable($dataArr = array(), $tablename)
    {
        $this->db->where($dataArr)->delete($tablename);
        return true;
    }

    //Uploading Images
    public function uploadImages($imageArr, $uploadPath, $filename, $maxSize = "200", $maxWidth = "", $maxHeight = "")
    {
        //$imageArr as 
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = '*';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($filename)) {
            $error = array('error' => $this->upload->display_errors());
            log_message('error', 'Uploading Problem ' . $this->upload->display_errors());
            return $error;
        } else {
            $data = array('upload_data' => $this->upload->data());
            return $data;
        }
    }

    // Get Customers List By Omitting Records with UserSN
    function getCustomersListByOmitRecords($dataArr = array())
    {
        $sql = 'select * from customers where sn NOT IN (select selectSN from keep_omit where identify = ? and flag = ? and userSN = ?) ORDER BY sn DESC';
        $result = $this->db->query($sql, $dataArr);
        return $result->result_array();
    }

    // Get Customers List By Omitting Records with UserSN
    function getproductsLocations($dataArr = array())
    {
        $sql = "select * from products_locations where adminSN = ? and sn not in (select selectSN from keep_omit where userSN={$dataArr['adminSN']} and identify ='PL' and flag='O')";
        $result = $this->db->query($sql, $dataArr['adminSN']);
        return $result->result_array();
    }

    //get any table
    function getTable($table, $dataArr = [], $pk = 'sn', $identify)
    {
        $sql = "select * from {$table} where adminSN = {$dataArr['adminSN']} and {$pk} not in (select selectSN from keep_omit where userSN={$dataArr['adminSN']} and identify = '$identify' and flag='O')";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    //Resizing Image
    function resizingImage(
        $filename,
        $uploadPath,
        $width = "",
        $height = "",
        $newPath = "",
        $createThumb = true,
        $maintainRatio = true
    ) {

        $config['image_library'] = 'gd2';
        $config['source_image'] = $uploadPath . $filename;
        $config['new_image'] = $newPath;
        // $config['create_thumb'] = $createThumb;
        $config['maintain_ratio'] = $maintainRatio;
        if (!empty($width)) {
            $config['width'] = $width;
        }
        if (!empty($height)) {
            $config['height'] = $height;
        }
        $this->load->library('image_lib');
        $this->image_lib->initialize($config);
        try {
            $this->image_lib->resize();
            $this->image_lib->clear();
            //return $newPath;
        } catch (Exception $e) {
            // log_message('error', 'Resizing Problem ' . $this->image_lib->display_errors());
            print_r($this->image_lib->display_errors());
        }
    }

    // Sending email
    public function sendEmail($from, $fromName, $to, $cc, $bcc, $message, $subject, $attach = array())
    {
        $this->load->library('email');
        //$config['protocol'] = 'sendmail';
        //  $config['charset'] = 'utf-8';
        $config['wordwrap'] = true;
        $config['mailtype'] = 'html';

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = 'sgmalways4u@gmail.com';
        $config['smtp_pass'] = '';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";

        $config['validation'] = true; // bool whether to validate email or not

        $this->email->initialize($config);
        $this->email->from($from, $fromName);
        $this->email->to($to);
        if (!empty($cc)) {
            $this->email->cc($cc);
        }
        if (!empty($bcc)) {
            $this->email->bcc($bcc);
        }

        $this->email->subject($subject);
        $this->email->message($message);
        if (!empty($attach)) {
            foreach ($attach as $attachment) {
                $this->email->attach($attachment);
            }
        }

        if (!$this->email->send()) {
            return false;
        } else {
            return true;
        }
    }

    public function sendEmail1($from, $fromName, $to, $cc, $bcc, $message, $subject, $attach = array())
    {
        $this->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = true;
        $config['mailtype'] = 'html';

        $this->email->initialize($config);
        $this->email->from($from, $fromName);
        $this->email->to($to);
        if (!empty($cc)) {
            $this->email->cc($cc);
        }
        if (!empty($bcc)) {
            $this->email->bcc($bcc);
        }

        $this->email->subject($subject);
        $this->email->message($message);
        if (!empty($attach)) {
            foreach ($attach as $attachment) {
                $this->email->attach($attachment);
            }
        }
        if (!$this->email->send()) {
            return false;
        } else {
            return true;
        }
    }

    public function deleteCustomers($customers)
    {
        return $this->db->query("delete from customers where sn in ($customers)");
    }

    public function deleteAttachment($sno)
    {
        return $this->db->query("delete from customers_attachment where sn='{$sno}'");
    }

    public function updateDescription($sn, $desc)
    {
        return $this->db->query("update customers_attachment set description='{$desc}' where sn='{$sn}'");
    }

    public function deleteEmail($sno)
    {
        return $this->db->query("delete from customers_email where sn='{$sno}'");
    }
}
