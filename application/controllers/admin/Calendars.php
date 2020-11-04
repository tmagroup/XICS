<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Calendars extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Event_model');
        $this->load->model('Eventstatus_model');
        $this->load->model('Expense_model');
        $this->load->model('User_model');
        $this->load->model('Assignment_model');
        $this->load->model('Lead_model');
    }

    /* List all events */
    public function index()
    {
        //$this->Event_model->getGoogleEvents('2018-10-30','2018-12-09');
        //$location1 = array(10.8505159,76.27108329999999);
        //$location2 = array(21.9619463,70.79229699999996);
        //echo $distance = $this->Event_model->getDistance($location1,$location2,0,false);
        //echo $distance = $this->Event_model->getDistance(10.8505159,76.27108329999999, 21.9619463,70.79229699999996, "K");

        if(!$GLOBALS['calendar_permission']['view']){
            access_denied('calendar');
        }

        //******************** Initialise ********************/
        //Get Google Calendar IDs
        $data['googlecalendars'] = $this->Event_model->getGoogleCalendarList();
        //Get Google System Calendar Color
        $data['getSystemCalendarColor'] = $this->Event_model->getSystemCalendarColor();
        //Get User Calendar ID
        $dataUser = $this->User_model->get('','googleCalendarIDs'," userid='".get_user_id()."' ");
        $data['calendarIds'] = explode(",",$dataUser[0]['googleCalendarIDs']);
        //******************** End Initialise ********************/

        $data['title'] = lang('page_calendar');

        $this->load->view('admin/calendars/manage', $data);
    }

    /* Add calendar event color by Ajax */
    public function getGoogleEventColor(){
        /* Google Method
        $colors = $this->Event_model->getGoogleEventColor();
        $system_colors = get_system_favourite_colors();

        $my_colors = array();
        foreach ($colors->getEvent() as $key => $color) {
            $my_colors[] = array('colorId'=>$key, 'background'=>$system_colors[$key], 'foreground'=>$color->getForeground());
        }
        echo json_encode($my_colors);*/

        /* System Method */
        $system_colors = $this->Event_model->getSystemEventColor();
        $my_colors = array();
        foreach ($system_colors as $key => $color) {
            $my_colors[] = array('colorId'=>$key, 'background'=>$color, 'foreground'=>'');
        }
        echo json_encode($my_colors);
        exit;
    }

    /* Add calendar event by Ajax */
    public function addEvent($id=''){

        //******************** Initialise ********************/
        if($id>0){
            //Event
            $data['event'] = (array) $this->Event_model->get($id);
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            if(isset($data['event']['eventid'])){

                //Get Event Data Before Update
                $eventData = (array) $this->Event_model->get($data['event']['eventid']);
                $response = $this->Event_model->update($post, $data['event']['eventid']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'event', 'actionid'=>$response, 'actiontitle'=>'event_updated');
                    do_action_history($Action_data);

                    /*
                    * After we edit and change Eventstatus to „Termin wahrgenommen“ >>
                    :: one Entrie in a new database will done. In the Table “Expenses“ will add this Infos: ExpensesID (Autogenatrated ID),
                    * Date (The Date of the Event), Distance (The Distance in km should be read out between the Eventstartadres and the Eventendsdres.
                    * I think here you should use Googlemaps APIs), UserID
                    */
                    if(isset($eventData['eventstatus']) && $eventData['eventstatus']==1){
                        if($post['eventstatus']==2){

                            //Get Distance
                            $distance = 0;
                            if($eventData['event_startaddress_lat']!="" && $eventData['event_startaddress_lng']!=""
                            && $eventData['event_address_lat']!="" && $eventData['event_address_lng']!=""){
                                /*$location1 = array($eventData['event_startaddress_lat'],$eventData['event_startaddress_lng']);
                                $location2 = array($eventData['event_address_lat'],$eventData['event_address_lng']);
                                $distance = $this->Event_model->getDistance($location1,$location2);*/
                                $content = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$eventData['event_startaddress_lat'].','.$eventData['event_startaddress_lng'].'&destinations='.$eventData['event_address_lat'].','.$eventData['event_address_lng'].'&key='.get_option('google_api_key'));
                                $content = json_decode($content);
                                $distance = @$content->rows[0]->elements[0]->distance->text;
                            }

                            $dataExpense = array('eventid'=>$data['event']['eventid'], 'start'=>_dt($post['start']), 'end'=>_dt($post['end']), 'distance'=>$distance);
                            $this->Expense_model->add($dataExpense);

                        }
                    }

                    echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_event'))));
                }
                else{
                    echo json_encode(array('response'=>'error','message'=>$response));
                }
            }
            else{
                $response = $this->Event_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'event', 'actionid'=>$response, 'actiontitle'=>'event_added');
                    do_action_history($Action_data);

                    echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_event'))));
                }
                else{
                    echo json_encode(array('response'=>'error','message'=>$response));
                }
            }
            exit;
        }

        //******************** Initialise ********************/
        //Eventstatus
        $data['eventstatus'] = $this->Eventstatus_model->get();
        $data['eventstatus'] = dropdown($data['eventstatus'],'id','name');

        //Assignments
        // $data['assignments'] = $this->Assignment_model->get('',"assignmentnr, company as name"," company!='' ");
        // $data['assignments'] = dropdown($data['assignments'],'assignmentnr','name');
        $data['assignments'] = $this->Customer_model->get('', 'customernr, company, ', array()," company != '' AND company IS NOT NULL ");
        $data['assignments'] = dropdown($data['assignments'], 'customernr', 'company');

        //Leads
        $data['leads'] = $this->Lead_model->get('',"leadnr, company as name", array()," company!='' ");
        $data['leads'] = dropdown($data['leads'],'leadnr','name');
        //Supporter
        //$data['proofusers'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(5) ");
        //$data['proofusers'] = dropdown($data['proofusers'],'userid','name');

        //Get Google Calendar IDs
        $data['googlecalendars'] = $this->Event_model->getGoogleCalendarList();
        //Get Google System Calendar Color
        // $data['getSystemCalendarColor'] = $this->Event_model->getSystemCalendarColor();
        $data['getSystemCalendarColor'] = $this->Event_model->getSystemEventColor();
        //Get User Calendar ID
        $dataUser = $this->User_model->get('','googleCalendarIDs'," userid='".get_user_id()."' ");
        $calendarIds = explode(",",$dataUser[0]['googleCalendarIDs']);

        //Original Calendar ID Assigned For User
        $dataAssignedCalendarID = array();
        foreach($data['googlecalendars'] as $googlecalendar){
            if(in_array($googlecalendar['id'], $calendarIds) && count($calendarIds)>0){
                $dataAssignedCalendarID[] = $googlecalendar;
            }
        }
        $data['dataAssignedCalendarIDs'] = $dataAssignedCalendarID;
        //******************** End Initialise ********************/

        $this->load->view('admin/calendars/addEvent', $data);
    }

    /* Detail calendar event */
    public function detail($id){

        //Event
        //******************** Initialise ********************/
        if($id>0){
            //Event
            $data['event'] = (array) $this->Event_model->get($id,"tblevents.*, IF(tblevents.assignmentnr>0,(SELECT company FROM tblassignments WHERE assignmentnr=tblevents.assignmentnr),IF(tblevents.leadnr>0,(SELECT company FROM tblleads WHERE leadnr=tblevents.leadnr),'')) as event_company  , IF(tblevents.eventid>0,'CRM_EVENT','CRM_EVENT') as event_type, tbleventstatus.name as eventstatus_name, CONCAT(tblusers.name,' ',tblusers.surname) as full_name, tblexpenses.distance ",array('tbleventstatus'=>'tbleventstatus.id=tblevents.eventstatus','tblusers'=>'tblusers.userid=tblevents.userid', 'tblexpenses'=>'tblexpenses.eventid=tblevents.eventid'));
        }
        //******************** End Initialise ********************/

        if(empty($data['event']['eventid'])){
            set_alert('danger', sprintf(lang('failed'),lang('page_detail_event')));
            redirect(site_url('admin/calendars/'));
        }

        $this->load->view('admin/calendars/detail', $data);
    }

    /* Detail calendar event by Ajax */
    public function detailEvent($id, $google_eid='', $calendarId='') {

        //URL decode
        $calendarId = str_replace("_has_","#",$calendarId);
        $calendarId = str_replace("_at_","@",$calendarId);

        //******************** Initialise ********************/
        if($google_eid!="" && $google_eid!="0"){
            $data['event'] = $this->Event_model->getSingleGoogleEvent($google_eid,$calendarId);
        }
        else{
            if($id>0){
                //Event
                $data['event'] = (array) $this->Event_model->get($id,"tblevents.*, IF(tblevents.assignmentnr>0,(SELECT company FROM tblassignments WHERE assignmentnr=tblevents.assignmentnr),IF(tblevents.leadnr>0,(SELECT company FROM tblleads WHERE leadnr=tblevents.leadnr),'')) as event_company  , IF(tblevents.eventid>0,'CRM_EVENT','CRM_EVENT') as event_type, tbleventstatus.name as eventstatus_name, CONCAT(tblusers.name,' ',tblusers.surname) as full_name, tblexpenses.distance ",array('tbleventstatus'=>'tbleventstatus.id=tblevents.eventstatus','tblusers'=>'tblusers.userid=tblevents.userid', 'tblexpenses'=>'tblexpenses.eventid=tblevents.eventid'));
                $google_event = $this->Event_model->getSingleGoogleEventDirect($calendarId, $data['event']['google_eid']);
                $data['event']['responsive_to_name'] = $google_event['responsive_to_name'];
            }

            if(empty($data['event']['eventid'])){
                echo json_encode(array('response'=>'error','message'=>sprintf(lang('failed'),lang('page_detail_event'))));
                exit;
            }
        }
        //******************** End Initialise ********************/

        $this->load->view('admin/calendars/detailEvent', $data);
    }

    /* Get Events by Ajax */
    public function getEvents(){
        /*$_GET['start'] = '2018-09-30';
        $_GET['end'] = '2018-11-11';*/
        /* Sync from Google Events */
        $crm_event_ids = $this->Event_model->getGoogleEvents($_GET['start'], $_GET['end']);
        if(is_array($crm_event_ids) && count($crm_event_ids)>0){
            $crm_event_ids = implode(",",$crm_event_ids);
        }else{
            $crm_event_ids = 0;
        }

        /* CRM Events */
        if(isset($_POST['filter_googleCalendarIDs']) && count($_POST['filter_googleCalendarIDs'])>0){
            $filter_googleCalendarIDs = implode("','", $_POST['filter_googleCalendarIDs']);

            $events = $this->Event_model->get('',"tblevents.*, DATE_FORMAT(`start`, '%Y-%m-%d') as sdate, DATE_FORMAT(`end`, '%Y-%m-%d') as edate ",array()," "
                    . "((DATE_FORMAT(`start`, '%Y-%m-%d')>='".$_GET['start']."' AND DATE_FORMAT(`start`, '%Y-%m-%d')<='".$_GET['end']."') "
                    . " OR "
                    . "(DATE_FORMAT(`end`, '%Y-%m-%d')>='".$_GET['start']."' AND DATE_FORMAT(`end`, '%Y-%m-%d')<='".$_GET['end']."')) "
                    . " AND (public=1 OR userid='".get_user_id()."') AND calendarId IN('".$filter_googleCalendarIDs."') AND eventid IN(".$crm_event_ids.")  "); //hisself event and public event
        }
        else{

            $events = $this->Event_model->get('',"tblevents.*, DATE_FORMAT(`start`, '%Y-%m-%d') as sdate, DATE_FORMAT(`end`, '%Y-%m-%d') as edate ",array()," "
                    . "((DATE_FORMAT(`start`, '%Y-%m-%d')>='".$_GET['start']."' AND DATE_FORMAT(`start`, '%Y-%m-%d')<='".$_GET['end']."') "
                    . " OR "
                    . "(DATE_FORMAT(`end`, '%Y-%m-%d')>='".$_GET['start']."' AND DATE_FORMAT(`end`, '%Y-%m-%d')<='".$_GET['end']."')) "
                    . " AND (public=1 OR userid='".get_user_id()."') AND eventid IN(".$crm_event_ids.")  "); //hisself event and public event
        }

        $event_calendar = array();
        if(isset($events) && count($events)){
            foreach($events as $event){
                //$event_calendar[] = array('calendarId'=>$event['calendarId'],'id'=>$event['eventid'],'title'=>($event['title']),'start'=>str_replace(" ","T",$event['start']),'end'=>str_replace(" ","T",date("Y-m-d H:i:s",strtotime("1 day", strtotime($event['end'])))), 'color'=>$event['color']);
                //$event_calendar[] = array('calendarId'=>$event['calendarId'],'id'=>$event['eventid'],'title'=>($event['title']),'start'=>str_replace(" ","T",$event['sdate']),'end'=>str_replace(" ","T",$event['edate']), 'color'=>$event['color']);
                //$event_calendar[] = array('calendarId'=>$event['calendarId'],'id'=>$event['eventid'],'title'=>($event['title']),'start'=>$event['sdate'],'end'=>date("Y-m-d",strtotime("1 day", strtotime($event['edate']))), 'color'=>$event['color']);
                // $event_calendar[] = array('calendarId'=>$event['calendarId'],'id'=>$event['eventid'],'title'=>($event['title'].'__'.$event['sdate'].'__'.date("Y-m-d",strtotime("1 day", strtotime($event['edate'])))),'start'=>$event['sdate'],'end'=>date("Y-m-d",strtotime("1 day", strtotime($event['edate']))), 'color'=>$event['color']);
                $event_calendar[] = array(
                    'calendarId' => $event['calendarId'],
                    'id' => $event['eventid'],
                    // 'title' => ($event['title'] .' '. date('Y-m-d H:i:s', strtotime($event['start'])) .'_'. date('Y-m-d H:i:s', strtotime('1 day', strtotime($event['end'])))),
                    'title' => ($event['title'] .' '. date('H:i:s', strtotime($event['start'])) .' - '. date('H:i:s', strtotime('1 day', strtotime($event['end'])))), // strtotime($event['start']) .' ----- '.
                    'start' => date('Y-m-d', strtotime($event['sdate'])),
                    'end' => date('Y-m-d', strtotime('1 day', strtotime($event['edate']))),
                    'color' => $event['color'],
                    'timestemp' => strtotime($event['start'])
                );
            }
            // pdebug('$event_calendar before ----- ');
            // pdebug($event_calendar);
            /* DO NOT REMOVE
            usort($event_calendar, function($a, $b) {
                return $a['timestemp'] - $b['timestemp'];
            });
            */
            // pdebug('$event_calendar after ----- ');
            // pdebug($event_calendar, TRUE);
        }

        /* Sync from Google Events */
        /*$google_event_calendar = $this->Event_model->getGoogleEvents($_GET['start'], $_GET['end']);
        $event_calendar = array_merge($event_calendar, $google_event_calendar);*/

        echo json_encode($event_calendar);
        exit;
    }

    //Delete Event By Ajax
    public function deleteEvent(){
        $response = $this->Event_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'event', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'event_deleted');
            do_action_history($Action_data);

            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_event'))));
        }else{
            echo json_encode(array('response'=>'error','message'=>$response,'dataid'=>$this->input->post('id')));
        }
        exit;
    }

    //Delete Google Event By Ajax
    public function deleteGoogleEvent(){
        list($google_eid, $calendarId) = explode("[=]",$this->input->post('id'));
        $response = @$this->Event_model->deleteGoogleEvent($google_eid, $calendarId);
        if ($response==1) {
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_event'))));
        }else{
            echo json_encode(array('response'=>'error','message'=>$response,'dataid'=>$this->input->post('id')));
        }
        exit;
    }
}
