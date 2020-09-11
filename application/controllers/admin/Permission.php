<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends Admin_controller {
	
    public function __construct()
    {
        parent::__construct();           
    }
	
    public function index()
    {
        $data['title'] = lang('page_permission');
        $this->load->view('admin/permission_denied', $data);
    }
    
    public function denied()
    {
        $data['title'] = lang('page_permission');
        $this->load->view('admin/permission_denied', $data);
    }
}
