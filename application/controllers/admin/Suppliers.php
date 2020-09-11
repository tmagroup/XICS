<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Suppliers extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Supplier_model'); 
        $this->load->model('Salutation_model');
    }

    /* List all suppliers */
    public function index()
    {
        if(!$GLOBALS['supplier_permission']['view']){
            access_denied('supplier');
        }
             
        $data['title'] = lang('page_suppliers');
        $this->load->view('admin/suppliers/manage', $data);
    }
    
    /* List all suppliers by ajax */
    public function ajax()
    {
        $this->app->get_table_data('suppliers');        
    }
    
    /* Add/Edit Supplier */
    public function supplier($id='')
    {
        if(!$GLOBALS['supplier_permission']['create'] && !$GLOBALS['supplier_permission']['edit']){
            access_denied('supplier');
        }
        
        //******************** Initialise ********************/             
        if($id>0){
            //Supplier
            $data['supplier'] = (array) $this->Supplier_model->get($id); 
            
            if(empty($data['supplier']['suppliernr'])){
                redirect(site_url('admin/suppliers'));
            }
        }
        //******************** End Initialise ********************/
        
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();                       
            if(isset($data['supplier']['suppliernr'])){
                $response = $this->Supplier_model->update($post, $data['supplier']['suppliernr']);  
                if (is_numeric($response) && $response>0) {
                    
                    //History 
                    $Action_data = array('actionname'=>'supplier', 'actionid'=>$response, 'actiontitle'=>'supplier_updated');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_supplier')));                
                    redirect(site_url('admin/suppliers/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Supplier_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History 
                    $Action_data = array('actionname'=>'supplier', 'actionid'=>$response, 'actiontitle'=>'supplier_added');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('added_successfully'),lang('page_supplier')));
                    redirect(site_url('admin/suppliers/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            
            //Initialise                
            $suppliernr = '';
            if(isset($data['supplier'])){
                $suppliernr = $data['supplier']['suppliernr'];
            }
            $data['supplier'] = $post;
            $data['supplier']['suppliernr'] = $suppliernr;              
        }
        
        
        //******************** Initialise ********************/
        //Salutations
        $data['salutations'] = $this->Salutation_model->get();
        $data['salutations'] = dropdown($data['salutations'],'salutationid','name');  
        //******************** End Initialise ********************/
        
        //Page Title
        if(isset($data['supplier']['suppliernr']) && $data['supplier']['suppliernr']>0){
            $data['title'] = lang('page_edit_supplier');            
        }
        else{
            $data['title'] = lang('page_create_supplier');
        }            
        
       
        $this->load->view('admin/suppliers/supplier', $data);
    }
    
    /* Delete supplier */
    public function delete()
    {
        if(!$GLOBALS['supplier_permission']['delete'] || !$this->input->post('id')){
            access_denied('supplier');
        }
        
        $response = $this->Supplier_model->delete($this->input->post('id'));
        if ($response==1) {
            
            //History 
            $Action_data = array('actionname'=>'supplier', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'supplier_deleted');
            do_action_history($Action_data);
            
            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_supplier')));
        }else{
            set_alert('danger', $response);
        }            
        redirect(site_url('admin/suppliers/'));
    }
}
