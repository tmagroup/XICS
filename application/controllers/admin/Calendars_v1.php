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
        
        //Get Google Calendar IDs
        $data['googlecalendars'] = $this->Event_model->getGoogleCalendarList();
        //Get Google System Calendar Color
        $data['getSystemCalendarColor'] = $this->Event_model->getSystemCalendarColor();
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
    
    /* Detail calendar event by Ajax */
    public function detailEvent($id, $google_eid='', $calendarId=''){
        
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
                $data['event'] = (array) $this->Event_model->get($id,"tblevents.*, IF(tblevents.eventid>0,'CRM_EVENT','CRM_EVENT') as event_type, tbleventstatus.name as eventstatus_name, CONCAT(tblusers.name,' ',tblusers.surname) as full_name, tblexpenses.distance ",array('tbleventstatus'=>'tbleventstatus.id=tblevents.eventstatus','tblusers'=>'tblusers.userid=tblevents.userid', 'tblexpenses'=>'tblexpenses.eventid=tblevents.eventid'));            
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
        
        /* CRM Events */
        if(isset($_POST['filter_googleCalendarIDs']) && count($_POST['filter_googleCalendarIDs'])>0){            
            $filter_googleCalendarIDs = implode("','", $_POST['filter_googleCalendarIDs']);  
            
            $events = $this->Event_model->get('','',array()," "
                    . "((DATE_FORMAT(`start`, '%Y-%m-%d')>='".$_GET['start']."' AND DATE_FORMAT(`start`, '%Y-%m-%d')<='".$_GET['end']."') "
                    . " OR "
                    . "(DATE_FORMAT(`end`, '%Y-%m-%d')>='".$_GET['start']."' AND DATE_FORMAT(`end`, '%Y-%m-%d')<='".$_GET['end']."')) "
                    . " AND (public=1 OR userid='".get_user_id()."') AND calendarId IN('".$filter_googleCalendarIDs."') "); //hisself event and public event            
        }
        else{
            $events = $this->Event_model->get('','',array()," "
                    . "((DATE_FORMAT(`start`, '%Y-%m-%d')>='".$_GET['start']."' AND DATE_FORMAT(`start`, '%Y-%m-%d')<='".$_GET['end']."') "
                    . " OR "
                    . "(DATE_FORMAT(`end`, '%Y-%m-%d')>='".$_GET['start']."' AND DATE_FORMAT(`end`, '%Y-%m-%d')<='".$_GET['end']."')) "
                    . " AND (public=1 OR userid='".get_user_id()."')  "); //hisself event and public event
        }
        
        $event_calendar = array();
        if(isset($events) && count($events)){
            foreach($events as $event){
                $event_calendar[] = array('calendarId'=>$event['calendarId'],'id'=>$event['eventid'],'title'=>($event['title']),'start'=>str_replace(" ","T",$event['start']),'end'=>str_replace(" ","T",date("Y-m-d H:i:s",strtotime("1 day", strtotime($event['end'])))), 'color'=>$event['color']);
            }
        }
        
        /* Sync from Google Events */
        $google_event_calendar = $this->Event_model->getGoogleEvents($_GET['start'], $_GET['end']);
        $event_calendar = array_merge($event_calendar, $google_event_calendar);
        
        echo json_encode($event_calendar);
        exit;
    }
        
    //Delete Event By Ajax
    public function deleteEvent(){        
        $response = $this->Event_model->delete($this->input->post('id'));
        if ($response==1) {                
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_event'))));
        }else{                
            echo json_encode(array('response'=>'error','message'=>$response,'dataid'=>$this->input->post('id')));
        } 
        exit;
    }
}