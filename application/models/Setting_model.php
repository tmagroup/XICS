<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Setting_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Customer_model');
    }

    /**
     * Update update Profile
     * @param  array $data update Profile
     * @param  mixed $id   user id
     * @return boolean
     */
    public function updateProfile($data, $id)
    {
        $table = 'tblusers';
        $aid = 'userid';

        //Check Email
        $this->db->where($aid.'!=', $id);
        $this->db->where('email', trim($data['email']));
        $email = $this->db->get($table)->row();
        if ($email) {
            return lang('page_form_validation_email_already_exists');
        }

        //Check Customer Email
        $this->db->where('email', trim($data['email']));
        $c_email = $this->db->get('tblcustomers')->row();
        if ($c_email) {
            return lang('page_form_validation_email_already_exists');
        }

        //Password Encrpted
        if($data['password']!=""){
            $this->load->helper('phpass');
            $original_password = $data['password'];
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $data['password'] = $hasher->HashPassword($data['password']);
        }else{
            unset($data['password']);
        }

        //Unnecessory data
        unset($data['cpassword']);

        //Database data
        $this->db->where($aid, $id);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() > 0) {
            $rowfield = $this->User_model->get($id,'name');
            $this->db->query("UPDATE ".$table." SET `updated`='".date('Y-m-d H:i:s')."' WHERE ".$aid."='".$id."' ");
            //Log Activity
            logActivity('Profile Updated [ID: ' . $id . ', ' . $rowfield->name . ']');
        }

        return $id;
    }

    /**
     * Update update Profile
     * @param  array $data update Profile
     * @param  mixed $id   customer id
     * @return boolean
     */
    public function cupdateProfile($data, $id)
    {
        $table = 'tblcustomers';
        $aid = 'customernr';

        //Check Email
        $this->db->where($aid.'!=', $id);
        $this->db->where('email', trim($data['email']));
        $email = $this->db->get($table)->row();
        if ($email) {
            return lang('page_form_validation_email_already_exists');
        }

        //Check Staff Email
        $this->db->where('email', trim($data['email']));
        $s_email = $this->db->get('tblusers')->row();
        if ($s_email) {
            return lang('page_form_validation_email_already_exists');
        }

        //Password Encrpted
        if($data['password'] != ""){
            $this->load->helper('phpass');
            $original_password = $data['password'];
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $data['password'] = $hasher->HashPassword($data['password']);
        }else{
            unset($data['password']);
        }

        if(isset($data['invoice_email'])) {
            $data['invoice_email'] = '1';
        } else {
            $data['invoice_email'] = '0';
        }

        if(isset($data['invoice_cus'])) {
            $data['invoice_cus'] = $data['invoice_cus'];
        } else {
            $data['invoice_cus'] = '0';
        }
        //Unnecessory data
        unset($data['cpassword']);

        //Database data
        $this->db->where($aid, $id);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() > 0) {
            $rowfield = $this->Customer_model->get($id,'name');
            $this->db->query("UPDATE ".$table." SET `updated`='".date('Y-m-d H:i:s')."' WHERE ".$aid."='".$id."' ");
            //Log Activity
            logActivity('Profile Updated [ID: ' . $id . ', ' . $rowfield->name . ']');
        }

        return $id;
    }
}
