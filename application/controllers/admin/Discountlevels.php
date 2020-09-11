<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Discountlevels extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Discountlevel_model');
        $this->load->model('Field_model');
    }

    /* List all discountlevel */
    public function index()
    {
        if(!$GLOBALS['discountlevel_permission']['view']){
            access_denied('discountlevel');
        }

        $data['title'] = lang('page_discountlevel');
        $this->load->view('admin/discountlevels/manage', $data);
    }

    /* List all discountlevel by ajax */
    public function ajax()
    {
        $this->app->get_table_data('discountlevels');
    }

    /* Add/Edit Discountlevel */
    public function discount($id='')
    {
        if(!$GLOBALS['discountlevel_permission']['create'] && !$GLOBALS['discountlevel_permission']['edit']){
            access_denied('discountlevel');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Discountlevel
            $data['discountlevel'] = (array) $this->Discountlevel_model->get($id);

            if(empty($data['discountlevel']['discountnr'])){
                redirect(site_url('admin/discountlevels'));
            }
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            if(isset($data['discountlevel']['discountnr'])){
                $response = $this->Discountlevel_model->update($post, $data['discountlevel']['discountnr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'discountlevel', 'actionid'=>$response, 'actiontitle'=>'discountlevel_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_discountlevel')));
                    redirect(site_url('admin/discountlevels/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Discountlevel_model->add($post);
                if (is_numeric($response) && $response>0) {
                    $post['discountnr'] = $response;
                    $this->Field_model->add($post);

                    //History
                    $Action_data = array('actionname'=>'discountlevel', 'actionid'=>$response, 'actiontitle'=>'discountlevel_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_discountlevel')));
                    redirect(site_url('admin/discountlevels/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $discountnr = '';
            if(isset($data['discountlevel'])){
                $discountnr = $data['discountlevel']['discountnr'];
            }
            $data['discountlevel'] = $post;
            $data['discountlevel']['discountnr'] = $discountnr;
        }


        //Page Title
        if(isset($data['discountlevel']['discountnr']) && $data['discountlevel']['discountnr']>0){
            $data['title'] = lang('page_edit_discountlevel');
        }
        else{
            $data['title'] = lang('page_create_discountlevel');
        }


        $this->load->view('admin/discountlevels/discount', $data);
    }

    /* Delete discount level */
    public function delete()
    {
        if(!$GLOBALS['discountlevel_permission']['delete'] || !$this->input->post('id')){
            access_denied('discountlevel');
        }

        $response = $this->Discountlevel_model->delete($this->input->post('id'));
        if ($response==1) {

            $this->db->where('discountnr', $this->input->post('id'));
            $this->db->delete('tblfields');

            //History
            $Action_data = array('actionname'=>'discountlevel', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'discountlevel_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_discountlevel')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/discountlevels/'));
    }

    /* Import Discountlevel */
    public function import(){
        if(!$GLOBALS['discountlevel_permission']['import']){
            access_denied('discountlevel');
        }

        //Initialize
        $data['file_name'] = 'tbldiscountlevels';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);
        $data['not_importable'] = array('discountnr','created','updated');
        $data['sample_data'] = array('Sample Title','100','XXXX-XXXX-XXXX-XXXX');

        //Submit for Import
        if ($this->input->post()) {
            if($this->input->post('download_sample') === 'true'){
                //Download Sample CSV
                downloadsamplecsv($data);
            }
            else{
                //Import CSV
                $response = $this->Discountlevel_model->importcsv($data);
                if ($response['status']==1) {

                    //History
                    $Action_data = array('actionname'=>'discountlevel', 'actiontitle'=>'discountlevel_imported');
                    do_action_history($Action_data);

                    set_alert('success', $response['message']);
                    redirect(site_url('admin/discountlevels/'));
                }else{
                    set_alert('danger', $response['message']);
                }
            }
        }

        $data['title'] = lang('page_import_discountlevel');
        $this->load->view('admin/discountlevels/import', $data);
    }

    public function export_excel() {
        $data = $this->Discountlevel_model->get('','discounttitle,discountvalue,cardnumber');
        $header = array(lang('page_dt_discounttitle'),lang('page_dt_discountvalue'),lang('page_dt_cardnumber'));

        $filename = lang('page_manage_discountlevel').'_'.date('dmY').'.csv';
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