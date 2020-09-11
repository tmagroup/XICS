<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Documents extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Document_model');
        $this->load->model('File_model');      
        $this->load->model('Documentsetting_model');      
    }

    /* List all documents */
    public function index()
    {
        if(!$GLOBALS['document_permission']['view'] && !$GLOBALS['document_permission']['view_own']){
            access_denied('document');
        }
        
        //******************** Initialise ********************/
        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname'); 
        //******************** End Initialise ********************/
        
        $data['document'] = (array) $this->Document_model->get(get_user_id());   
        $data['title'] = lang('page_documents');
        $this->load->view('admin/documents/detail', $data);
    }
    
    /* List all documents by ajax */
    public function ajax()
    {
        $this->app->get_table_data('documents');        
    }
    
    /* Upload Dropzone file by Ajax */
    public function uploadDocuments($id){
        if(get_user_role()=='customer'){            
            handle_customerdocument_attachments($id);
        }
        else{
            handle_userdocument_attachments($id);
        }        
        exit;
    }
    
    /* Get Uploaded Documents by Ajax */
    public function getDocuments($id){        
        //******************** Initialise ********************/
        //Document
        $data['document'] = (array) $this->Document_model->get($id);            
        //******************** End Initialise ********************/
        
        if(count($data['document']['attachments']) > 0) {
            $this->load->view('admin/documents/documents_attachments_template', array('attachments'=>$data['document']['attachments']));
        }            
    }
    
    /* Delete Document by Ajax */
    public function deleteDocument(){
        if($this->input->post('id')){
            $response = $this->Document_model->delete_document_attachment($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_document'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }    
        exit;
    }
    
    /* Download Document */
    public function downloadDocument($attachmentid){
        $this->db->where('id', $attachmentid);
        $attachment = $this->db->get('tblfiles')->row();
        if (!$attachment) {
            die('No attachment found in database');
        }
        
        if(get_user_role()=='customer'){
            $roledoc = 'customerdocument';
        }
        else{
            $roledoc = 'userdocument';
        }
        
        $path = get_upload_path_by_type($roledoc) . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
    }
}