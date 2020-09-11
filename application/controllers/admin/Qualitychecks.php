<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Qualitychecks extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Qualitycheck_model');
        $this->load->model('Qualitycheckstatus_model');
        $this->load->model('User_model');
        $this->load->model('Customer_model');
        $this->load->model('Event_model');
    }

    /* List all qualitychecks */
    public function index()
    {
        if(!$GLOBALS['qualitycheck_permission']['view']){
            access_denied('qualitycheck');
        }

        //******************** Initialise ********************/
        //Responsibles (Users of Customer)
        $data['filter_responsible'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible')
        );
        $data['filter_responsible'] = dropdown($data['filter_responsible'],'userid','name');

        //Qualitycheckstatus
        $data['filter_qualitycheckstatus'] = $this->Qualitycheckstatus_model->get();
        $data['filter_qualitycheckstatus'] = dropdown($data['filter_qualitycheckstatus'],'id','name');
        //******************** End Initialise ********************/

        $data['title'] = lang('page_qualitychecks');
        $this->load->view('admin/qualitychecks/manage', $data);
    }

    /* List all qualitychecks by ajax */
    public function ajax($filter_responsible='',$filter_qualitycheckstatus='')
    {
        //Filter By responsible, qualitycheckstatus
        $params = array('filter_responsible'=>$filter_responsible,'filter_qualitycheckstatus'=>$filter_qualitycheckstatus);
        $this->app->get_table_data('qualitychecks',$params);
    }

    /* Add/Edit Qualitycheck */
    public function qualitycheck($id)
    {
        if(!$GLOBALS['qualitycheck_permission']['edit']){
            access_denied('qualitycheck');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Qualitycheck
            $data['qualitycheck'] = (array) $this->Qualitycheck_model->get($id,"tblqualitychecks.*, CONCAT(responsible.name,' ',responsible.surname) as responsible,"

                    . "(CASE rel_type

                        WHEN 'assignment' THEN (SELECT assignmentnr_prefix FROM tblassignments WHERE assignmentnr=tblqualitychecks.rel_id)

                        WHEN 'hardwareassignment' THEN (SELECT hardwareassignmentnr_prefix FROM tblhardwareassignments WHERE hardwareassignmentnr=tblqualitychecks.rel_id)

                        WHEN 'event' THEN (SELECT title FROM tblevents WHERE eventid=tblqualitychecks.rel_id)

                        ELSE ''

                    END) as rel_name",
                    array('tblusers as responsible'=>'responsible.userid=tblqualitychecks.responsible')
            );
        }

        if(empty($data['qualitycheck']['qualitychecknr'])){
            redirect(site_url('admin/qualitychecks'));
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Qualitycheck_model->update($post, $data['qualitycheck']['qualitychecknr']);
            if (is_numeric($response) && $response>0) {
                set_alert('success', sprintf(lang('updated_successfully'),lang('page_qualitycheck')));
                redirect(site_url('admin/qualitychecks/'));
                exit;
            }
            else{
                set_alert('danger', $response);
            }

            //Initialise
            $qualitychecknr = '';
            if(isset($data['qualitycheck'])){
                $qualitychecknr = $data['qualitycheck']['qualitychecknr'];
            }
            $data['qualitycheck'] = $post;
            $data['qualitycheck']['qualitychecknr'] = $qualitychecknr;
        }


        //******************** Initialise ********************/
        //Supporter
        $data['proofusers'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(5) ");
        $data['proofusers'] = dropdown($data['proofusers'],'userid','name');

        //Qualitycheckstatus
        $data['qualitycheckstatus'] = $this->Qualitycheckstatus_model->get();
        $data['qualitycheckstatus'] = dropdown($data['qualitycheckstatus'],'id','name');
        //******************** End Initialise ********************/

        //Page Title
        $data['title'] = lang('page_edit_qualitycheck');
        $this->load->view('admin/qualitychecks/qualitycheck', $data);
    }

    /* Detail Qualitycheck */
    public function detail($id='')
    {
        if(!$GLOBALS['qualitycheck_permission']['view']){
            access_denied('qualitycheck');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Qualitycheck
            $data['qualitycheck'] = (array) $this->Qualitycheck_model->get($id,"tblqualitychecks.*, CONCAT(proofuser.name,' ',proofuser.surname) as proofuser,"
                    . " tblqualitycheckstatus.name as qualitycheckstatus,
                    IF(rel_type='event', tblevents.calendarId, '') AS calendarId,
                    IF(rel_type='event',
                        IF(tblevents.assignmentnr>0,
                        (SELECT company FROM tblassignments WHERE assignmentnr=tblevents.assignmentnr),
                        IF(tblevents.leadnr>0,(SELECT company FROM tblleads WHERE leadnr=tblevents.leadnr),'')),
                        tblcustomers.company
                    ) as company,
                    IF(rel_type='event',
                        (SELECT CONCAT(eventuser.name,' ',eventuser.surname) FROM tblusers AS eventuser WHERE eventuser.userid=tblevents.userid),
                        (CONCAT(responsible.name,' ',responsible.surname))
                    ) as responsible,
                    "
                    . "(CASE rel_type

                        WHEN 'assignment' THEN (SELECT assignmentnr_prefix FROM tblassignments WHERE assignmentnr=tblqualitychecks.rel_id)

                        WHEN 'hardwareassignment' THEN (SELECT hardwareassignmentnr_prefix FROM tblhardwareassignments WHERE hardwareassignmentnr=tblqualitychecks.rel_id)

                        WHEN 'event' THEN (SELECT title FROM tblevents WHERE eventid=tblqualitychecks.rel_id)

                        ELSE ''

                    END) as rel_name, rel_type",

                    array('tblusers as responsible'=>'responsible.userid=tblqualitychecks.responsible',
                    'tblusers as proofuser'=>'proofuser.userid=tblqualitychecks.proofuser',
                    'tblevents as tblevents'=>'tblevents.eventid=tblqualitychecks.rel_id',
                    'tblcustomers as tblcustomers'=>'tblcustomers.customernr=tblqualitychecks.company',
                    'tblqualitycheckstatus'=>'tblqualitycheckstatus.id=tblqualitychecks.qualitycheckstatus')
            );

            $googlecalendars = $this->Event_model->getGoogleCalendarList();
            $new_arr = array();
            foreach ($googlecalendars as $key => $value) {
                $new_arr[$value['id']] = $value['summary'];
            }
            $googlecalendars = $new_arr;
            if ($data['qualitycheck']['rel_type']=='event' && isset($googlecalendars[$data['qualitycheck']['calendarId']]) && strtolower($googlecalendars[$data['qualitycheck']['calendarId']]) != 'primary') {
                $data['qualitycheck']['responsible'] = $googlecalendars[$data['qualitycheck']['calendarId']];
            }
        }

        if(empty($data['qualitycheck']['qualitychecknr'])){
            redirect(site_url('admin/qualitychecks'));
        }
        //******************** End Initialise ********************/

        //Page Title
        $data['title'] = lang('page_detail_qualitycheck');
        $this->load->view('admin/qualitychecks/detail', $data);
    }

    /* Delete qualitycheck */
    public function delete()
    {
        if(!$GLOBALS['qualitycheck_permission']['delete'] || !$this->input->post('id')){
            access_denied('qualitycheck');
        }

        $response = $this->Qualitycheck_model->delete($this->input->post('id'));
        if ($response==1) {
            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_qualitycheck')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/qualitychecks/'));
        exit;
    }
}