<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareinputs extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Hardwareinput_model');
        $this->load->model('Hardwareinputproduct_model');
        $this->load->model('Supplier_model');
        $this->load->model('Hardware_model');        
    }

    /* List all hardwareinputs */
    public function index()
    {
        if(!$GLOBALS['hardwareinput_permission']['view']){
            access_denied('hardwareinput');
        }
        
        //******************** Initialise ********************/
        $data['filter_lampsymbol'] = array(''=>lang('page_option_select'),'red'=>'Red','green'=>'Green','yellow'=>'Yellow');
        //******************** End Initialise ********************/
        
        $data['title'] = lang('page_hardwareinputs');
        $this->load->view('admin/hardwareinputs/manage', $data);
    }
    
    /* List all hardwareinputs by ajax */
    public function ajax($filter_lampsymbol='')
    {
        //Filter By lampsymbol
        $params = array('filter_lampsymbol'=>$filter_lampsymbol);	        
        $this->app->get_table_data('hardwareinputs',$params);        
    }
	
    /* Add/Edit Hardwareinput */
    public function hardwareinput($id='')
    {
        if(!$GLOBALS['hardwareinput_permission']['create'] && !$GLOBALS['hardwareinput_permission']['edit']){
            access_denied('hardwareinput');
        }
        
        //******************** Initialise ********************/
        if($id>0){
            //Hardwareinput
            $data['hardwareinput'] = (array) $this->Hardwareinput_model->get($id);            
        }
        //******************** End Initialise ********************/
         
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post(); 
            
            if(isset($data['hardwareinput']['hardwareinputnr'])){
                $response = $this->Hardwareinput_model->update($post, $data['hardwareinput']['hardwareinputnr']);  
                if (is_numeric($response) && $response>0) {
                    
                    //History 
                    $Action_data = array('actionname'=>'hardwareinput', 'actionid'=>$response, 'actiontitle'=>'hardwareinput_updated');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_hardwareinput')));                    
                    redirect(site_url('admin/hardwareinputs/'));    
                    exit;
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Hardwareinput_model->add($post);
                if (is_numeric($response) && $response>0) {
                    
                    //History 
                    $Action_data = array('actionname'=>'hardwareinput', 'actionid'=>$response, 'actiontitle'=>'hardwareinput_added');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('added_successfully'),lang('page_hardwareinput')));
                    redirect(site_url('admin/hardwareinputs/'));
                    exit;
                }
                else{
                    set_alert('danger', $response);
                }
            }
            
            //Initialise    
            $hardwareinputnr = '';
            if(isset($data['hardwareinput'])){
                $hardwareinputnr = $data['hardwareinput']['hardwareinputnr'];
            }
            $data['hardwareinput'] = $post;
            $data['hardwareinput']['hardwareinputnr'] = $hardwareinputnr;
        }
        
        
        //******************** Initialise ********************/
        //Supplier
        $data['suppliers'] = $this->Supplier_model->get("","suppliernr, CONCAT(name,' ',surname) as suppliername");
        $data['suppliers'] = dropdown($data['suppliers'],'suppliernr','suppliername'); 
        
        //Hardware
        $data['hardwares'] = $this->Hardware_model->get();
        $data['hardwares'] = dropdown($data['hardwares'],'hardwarenr','hardwaretitle'); 
        
        //Hardwareinput Products
        $hardwareinputnr = isset($data['hardwareinput']['hardwareinputnr'])?$data['hardwareinput']['hardwareinputnr']:'';
        $data['hardwareinputproducts'] = $this->Hardwareinputproduct_model->get('','', array()," hardwareinputnr='".$hardwareinputnr."' ");
        //******************** End Initialise ********************/
        
        
        //Page Title
        if(isset($data['hardwareinput']['hardwareinputnr']) && $data['hardwareinput']['hardwareinputnr']>0){
            $data['title'] = lang('page_edit_hardwareinput');           
        }
        else{
            $data['title'] = lang('page_create_hardwareinput');
        }            
        
       
        $this->load->view('admin/hardwareinputs/hardwareinput', $data);
    }
    
    /* Detail Hardwareinput */
    public function detail($id='')
    {        	
        if(!$GLOBALS['hardwareinput_permission']['view']){
            access_denied('hardwareinput');
        }
        
        //******************** Initialise ********************/
        if($id>0){
            //Hardwareinput
            $data['hardwareinput'] = (array) $this->Hardwareinput_model->get($id,"tblhardwareinputs.*, "
                    . " CONCAT(supplier.name,' ',supplier.surname) as suppliername ",
                    
                    array('tblsuppliers as supplier'=>'supplier.suppliernr=tblhardwareinputs.supplier')                    
            ); 
        }

        if(empty($data['hardwareinput']['hardwareinputnr'])){
            redirect(site_url('admin/hardwareinputs'));
        }
        //******************** End Initialise ********************/
         
        
        //******************** Initialise ********************/
        //Hardwareinput Products        
        $data['hardwareinputproducts'] = $this->Hardwareinputproduct_model->get('','tblhardwareinputproducts.*, '
                . 'tblhardwares.hardwaretitle as hardware', 
                
                array('tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareinputproducts.hardware'),
                
            " tblhardwareinputproducts.hardwareinputnr='".$data['hardwareinput']['hardwareinputnr']."' "
        );
        //******************** End Initialise ********************/
        
        
        //Page Title
        $data['title'] = lang('page_detail_hardwareinput');
        $this->load->view('admin/hardwareinputs/detail', $data);
    }
    
    /* Delete hardwareinput */
    public function delete()
    {
        if(!$GLOBALS['hardwareinput_permission']['delete'] || !$this->input->post('id')){
            access_denied('hardwareinput');
        }
        
        $response = $this->Hardwareinput_model->delete($this->input->post('id'));
        if ($response==1) {
            
            //History 
            $Action_data = array('actionname'=>'hardwareinput', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'hardwareinput_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_hardwareinput')));
        }else{
            set_alert('danger', $response);
        }            
        redirect(site_url('admin/hardwareinputs/'));
        exit;
    }
    
    /* Delte Hardware Input Product */
    public function deleteHardwareinputProduct(){
        $response = $this->Hardwareinputproduct_model->delete($this->input->post('id'));
        if ($response==1) {                
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_hardwareinput_product')),'dataid'=>$this->input->post('id')));
        }else{                
            echo json_encode(array('response'=>'error','message'=>$response,'dataid'=>$this->input->post('id')));
        } 
        exit;
    }
}