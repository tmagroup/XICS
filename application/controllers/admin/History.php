<?php
defined('BASEPATH') or exit('No direct script access allowed');
class History extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('History_model');
        $this->load->model('User_model'); 
        $this->load->model('Customer_model'); 
        $this->load->model('Action_model'); 
    }

    /* List all history */
    public function index()
    {
        if(!$GLOBALS['history_permission']['view']){
            access_denied('history');
        }
        
        //******************** Initialise ********************/
        //Users
        $data['filter_user'] = $this->User_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name");        
        $data['filter_user'] = dropdown($data['filter_user'],'userid','name');
        
        //Customers
        $data['filter_customer'] = $this->Customer_model->get('',"tblcustomers.customernr, CONCAT(tblcustomers.name,' ',tblcustomers.surname) as name");        
        $data['filter_customer'] = dropdown($data['filter_customer'],'customernr','name');
        
        //Actions
        $data['filter_action'] = $this->Action_model->get('',"name,actionname");        
        $data['filter_action'] = dropdown($data['filter_action'],'actionname','name');
        //******************** End Initialise ********************/
        
        $data['title'] = lang('page_history');
        $this->load->view('admin/histories/manage', $data);
    }
    
    /* List all history by ajax */
    public function ajax($filter_user='',$filter_customer='',$filter_action='')
    {
        //Filter By user or customer and action
        $params = array('filter_user'=>$filter_user,'filter_customer'=>$filter_customer,'filter_action'=>$filter_action);	        
        $this->app->get_table_data('histories',$params);        
    }    
}