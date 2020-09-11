<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Infodocuments extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Infodocument_model');
        $this->load->model('File_model');
    }

    /* List all info documents */
    public function index()
    {
        if(!$GLOBALS['infodocument_permission']['view'] && !$GLOBALS['infodocument_permission']['view_own']){
            access_denied('infodocument');
        }

        $data['title'] = lang('page_infodocuments');
        $this->load->view('admin/infodocuments/manage', $data);
    }

    /* List all info documents by ajax */
    public function ajax()
    {
        $this->app->get_table_data('infodocuments');
    }

    /* Add Info document by Ajax */
    public function addInfodocument($id = ''){

        if ($id != '') {
            if(!$GLOBALS['infodocument_permission']['edit']){
                access_denied('infodocument');
            }

        } else {
            if(!$GLOBALS['infodocument_permission']['create']){
                access_denied('infodocument');
            }
        }

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            if ($id != '') {
                $response = $this->Infodocument_model->update($post, $id);
                if (is_numeric($response) && $response>0) {
                    handle_infodocument_attachments($response);
                    echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_infodocument'))));
                } else{
                    echo json_encode(array('response'=>'error','message'=>$response));
                }
            } else {
                $response = $this->Infodocument_model->add($post);
                if (is_numeric($response) && $response>0) {
                    handle_infodocument_attachments($response);
                    echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_infodocument'))));
                } else{
                    echo json_encode(array('response'=>'error','message'=>$response));
                }
            }
        }
        exit;
    }

    /* Delete Info document by Ajax */
    public function deleteInfodocument(){

        if(!$GLOBALS['infodocument_permission']['delete'] || !$this->input->post('id')){
            access_denied('infodocument');
        }

        $response = $this->Infodocument_model->delete($this->input->post('id'));
        if ($response==1) {
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_infodocument'))));
        }else{
            echo json_encode(array('response'=>'error','message'=>$response));
        }
        exit;
    }
}