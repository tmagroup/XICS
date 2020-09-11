<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rateslandline extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Ratelandline_model');
    }

    /* List all rateslandline */
    public function index()
    {
        if(!$GLOBALS['ratelandline_permission']['view']){
            access_denied('ratelandline');
        }

        $data['title'] = lang('page_rateslandline');
        $this->load->view('admin/rateslandline/manage', $data);
    }

    /* List all rateslandline by ajax */
    public function ajax()
    {
        $this->app->get_table_data('rateslandline');
    }

    /* Add/Edit Ratelandline */
    public function rate($id='')
    {
        if(!$GLOBALS['ratelandline_permission']['create'] && !$GLOBALS['ratelandline_permission']['edit']){
            access_denied('ratelandline');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Ratelandline
            $data['ratelandline'] = (array) $this->Ratelandline_model->get($id);

            if(empty($data['ratelandline']['ratenr'])){
                redirect(site_url('admin/rateslandline'));
            }
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            if(isset($data['ratelandline']['ratenr'])){
                $response = $this->Ratelandline_model->update($post, $data['ratelandline']['ratenr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'ratelandline', 'actionid'=>$response, 'actiontitle'=>'ratelandline_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_ratelandline')));
                    redirect(site_url('admin/rateslandline/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Ratelandline_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'ratelandline', 'actionid'=>$response, 'actiontitle'=>'ratelandline_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_ratelandline')));
                    redirect(site_url('admin/rateslandline/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $ratenr = '';
            if(isset($data['ratelandline'])){
                $ratenr = $data['ratelandline']['ratenr'];
            }
            $data['ratelandline'] = $post;
            $data['ratelandline']['ratenr'] = $ratenr;
        }


        //Page Title
        if(isset($data['ratelandline']['ratenr']) && $data['ratelandline']['ratenr']>0){
            $data['title'] = lang('page_edit_ratelandline');
        }
        else{
            $data['title'] = lang('page_create_ratelandline');
        }


        $this->load->view('admin/rateslandline/rate', $data);
    }

    /* Delete rate landline */
    public function delete()
    {
        if(!$GLOBALS['ratelandline_permission']['delete'] || !$this->input->post('id')){
            access_denied('ratelandline');
        }

        $response = $this->Ratelandline_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'ratelandline', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'ratelandline_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_ratelandline')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/rateslandline/'));
    }

    /* Import Ratelandline */
    public function import(){
        if(!$GLOBALS['ratelandline_permission']['import']){
            access_denied('ratelandline');
        }

        //Initialize
        $data['file_name'] = 'tblrateslandline';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);
        $data['not_importable'] = array('ratenr_prefix','created','updated');
        $data['sample_data'] = array(idprefix('ratelandline',1),'Sample Title','100');

        //Submit for Import
        if ($this->input->post()) {
            if($this->input->post('download_sample') === 'true'){
                //Download Sample CSV
                downloadsamplecsv($data);
            }
            else{
                //Import CSV
                $response = $this->Ratelandline_model->importcsv($data);
                if ($response['status']==1) {

                    //History
                    $Action_data = array('actionname'=>'ratelandline', 'actiontitle'=>'ratelandline_imported');
                    do_action_history($Action_data);

                    set_alert('success', $response['message']);
                    redirect(site_url('admin/rateslandline/'));
                }else{
                    set_alert('danger', $response['message']);
                }
            }
        }

        $data['title'] = lang('page_import_ratelandline');
        $this->load->view('admin/rateslandline/import', $data);
    }

    public function export_excel() {
        $data = $this->Ratelandline_model->get('','ratenr_prefix,ratetitle,price');
        $header = array(lang('page_dt_ratenr'),lang('page_dt_ratetitle'),lang('page_dt_price'));

        $filename = lang('page_manage_ratelandline').'_'.date('dmY').'.csv';
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'";');

        $f = fopen('php://output', 'w');
        foreach ($data as $key => $value) {
            if ($key==0) {
                fputcsv($f, array_combine(array_keys($value), $header), ',');
            }
            fputcsv($f, $value, ',');
        }
    }
}
