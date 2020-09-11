<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_controller {
	
    public function __construct()
    {
        parent::__construct();   
        $this->load->model('Event_model');
        $this->load->model('Reminder_model');
    }
    
    public function save_dashboard_widgets_order(){
        if ($this->input->post()) {
            $post = $this->input->post();  
            if($post['widget_role']=='user'){
                $this->db->query("UPDATE tblusers SET `dashboard_widgets_order`='".$post['widget_data']."' WHERE userid='".get_user_id()."' ");
            }
            else if($post['widget_role']=='customer'){
                $this->db->query("UPDATE tblcustomers SET `dashboard_widgets_order`='".$post['widget_data']."' WHERE customernr='".get_user_id()."' ");
            }
        }
        exit;
    }
    
    public function index()
    {
        //Points (See the Point of the acual month)
        $data['widget']['dashboard_points'] = $this->LastPoints();
        
        //Lead (Last 5 Leads with status "Nicht kontaktiert")
        $data['widget']['dashboard_leads'] = $this->LastLeads(1,'=',5);
        
        //Tickets (All Tickets with status !="Erledigt")
        $data['widget']['dashboard_tickets'] = $this->LastTickets(4,'!=');
        
        //Todos (Last 5 Todos with status !="Erledigt")
        $data['widget']['dashboard_todos'] = $this->LastTodos(3,'!=',5);
        
        //Assignment (Last 5 Assignments with status !="Vollständig")
        $data['widget']['dashboard_assignments'] = $this->LastAssignments(3,'!=',5);
        
        //Hardware Assignment (Last 5 Hardware Assignments with status="Offen")
        $data['widget']['dashboard_hardwareassignments'] = $this->LastHardwareAssignments(1,'=',5);
        
        //Hardware Invoice (Last 5 Hardware Invoices with Not Paid)
        $data['widget']['dashboard_hardwareinvoices'] = $this->LastHardwareInvoices(0,'=',5);
        
        //Monitorings (Last 5 Monitorings with status ="Erstellt")
        $data['widget']['dashboard_monitorings'] = $this->LastMonitorings(1,'=',5);
        
        //Quotations (Last 5 Quotations with status =“Erstellt”)
        $data['widget']['dashboard_quotations'] = $this->LastQuotations(1,'=',5);
        
        //Qualitychecks (List all QualityChecks with status ="Offen")
        $data['widget']['dashboard_qualitychecks'] = $this->LastQualityChecks(1,'=');
        
        //Listed the next comming 5 Event from the calender
        $data['widget']['dashboard_events'] = $this->LastGoogleEvents(1,'=',5);
        
        //Listed last 5 Notifications.
        $data['widget']['dashboard_notifications'] = $this->LastNotifications(0,'=',5);
        
        $data['title'] = lang('page_dashboard');
        $this->load->view('admin/dashboard', $data);
    }
    
    public function LastLeads($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
        
        //- On the Dashboard he should only Leads which belongs to the User who is logged in. (Salesman and Supporter and POS)
        if($GLOBALS['current_user']->userrole==5 || $GLOBALS['current_user']->userrole==6){
            $query = $this->db->query("SELECT leadnr, company, leadnr_prefix, (SELECT name FROM tblleadstatus WHERE id=leadstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblleads WHERE userid='".get_user_id()."' AND leadstatus ".$condition." '".$status."' ORDER BY leadnr DESC ".$limit);        
        }
        else{
            $query = $this->db->query("SELECT leadnr, company, leadnr_prefix, (SELECT name FROM tblleadstatus WHERE id=leadstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblleads WHERE leadstatus ".$condition." '".$status."' ORDER BY leadnr DESC ".$limit);        
        }
        return $query->result_array();
    }
    
    public function LastTickets($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
        
        //- On the Dashboard he should only Tickets which belongs to the User who is logged in. (Customer and Salesman and Supporter and POS)
        if(get_user_role()=='customer'){
            //$query = $this->db->query("SELECT ticketnr, tickettitle, ticketnr_prefix, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tbltickets WHERE userid='".get_user_id()."' AND userrole='customer' AND ticketstatus ".$condition." '".$status."' ORDER BY ticketnr DESC ".$limit);        
            $query = $this->db->query("SELECT ticketnr, tickettitle, ticketnr_prefix, (SELECT name FROM tblticketstatus WHERE id=ticketstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tbltickets WHERE userid='".get_user_id()."' AND userrole='customer' ORDER BY ticketnr DESC ".$limit);        
        }
        else if($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==5 || $GLOBALS['current_user']->userrole==6){
            $query = $this->db->query("SELECT ticketnr, tickettitle, ticketnr_prefix, (SELECT name FROM tblticketstatus WHERE id=ticketstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tbltickets WHERE userid='".get_user_id()."' AND userrole='user' AND ticketstatus ".$condition." '".$status."' ORDER BY ticketnr DESC ".$limit);        
        }else{
            $query = $this->db->query("SELECT ticketnr, tickettitle, ticketnr_prefix, (SELECT name FROM tblticketstatus WHERE id=ticketstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tbltickets WHERE ticketstatus ".$condition." '".$status."' ORDER BY ticketnr DESC ".$limit);        
        }    
        return $query->result_array();
    }
    
    public function LastTodos($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
        
        //- On the Dashboard he should only TODOs which belongs to the User who is logged in. (Salesman and Supporter)
        if($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==5){
            $query = $this->db->query("SELECT todonr, todotitle, todonr_prefix, (SELECT name FROM tbltodostatus WHERE id=todostatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tbltodos WHERE userid='".get_user_id()."' AND todostatus ".$condition." '".$status."' ORDER BY todonr DESC ".$limit);        
        }
        else{
            $query = $this->db->query("SELECT todonr, todotitle, todonr_prefix, (SELECT name FROM tbltodostatus WHERE id=todostatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tbltodos WHERE todostatus ".$condition." '".$status."' ORDER BY todonr DESC ".$limit);        
        }    
        return $query->result_array();
    }
    
    public function LastAssignments($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
                
        if(get_user_role()=='customer'){
            //$query = $this->db->query("SELECT assignmentnr, company, assignmentnr_prefix, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblassignments WHERE customer='".get_user_id()."' AND assignmentstatus ".$condition." '".$status."' ORDER BY assignmentnr DESC ".$limit);        
            $query = $this->db->query("SELECT assignmentnr, company, assignmentnr_prefix, (SELECT name FROM tblassignmentstatus WHERE id=assignmentstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblassignments WHERE customer='".get_user_id()."' ORDER BY assignmentnr DESC ".$limit);        
        }
        //- On the Dashboard he should only see Assignments which belongs to the User who is logged in. (Salesman)
        else if($GLOBALS['current_user']->userrole==3){
            $query = $this->db->query("SELECT assignmentnr, company, assignmentnr_prefix, (SELECT name FROM tblassignmentstatus WHERE id=assignmentstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblassignments WHERE userid='".get_user_id()."' AND assignmentstatus ".$condition." '".$status."' ORDER BY assignmentnr DESC ".$limit);        
        }
        //- He can see only Assignment where the POS was choosen.
        else if($GLOBALS['current_user']->userrole==6){
            $query = $this->db->query("SELECT assignmentnr, company, assignmentnr_prefix, (SELECT name FROM tblassignmentstatus WHERE id=assignmentstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblassignments WHERE recommend='".get_user_id()."' AND assignmentstatus ".$condition." '".$status."' ORDER BY assignmentnr DESC ".$limit);        
        }else{
            $query = $this->db->query("SELECT assignmentnr, company, assignmentnr_prefix, (SELECT name FROM tblassignmentstatus WHERE id=assignmentstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblassignments WHERE assignmentstatus ".$condition." '".$status."' ORDER BY assignmentnr DESC ".$limit);        
        }    
        return $query->result_array();
    }
    
    public function LastHardwareAssignments($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
                
        //- On the Dashboard he should only see Hardware Assignments which belongs to the User who is logged in. (Customer)
        if(get_user_role()=='customer'){
            //$query = $this->db->query("SELECT hardwareassignmentnr, company, hardwareassignmentnr_prefix, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblhardwareassignments WHERE customer='".get_user_id()."' AND hardwareassignmentstatus ".$condition." '".$status."' ORDER BY hardwareassignmentnr DESC ".$limit);        
            $query = $this->db->query("SELECT hardwareassignmentnr, company, hardwareassignmentnr_prefix, (SELECT name FROM tblhardwareassignmentstatus WHERE id=hardwareassignmentstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblhardwareassignments WHERE customer='".get_user_id()."' ORDER BY hardwareassignmentnr DESC ".$limit);        
            return $query->result_array();
        }
    }
    
    public function LastQuotations($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
        
        //- On the Dashboard he should only see Quotations which belongs to the User who is logged in. (Salesman)
        if($GLOBALS['current_user']->userrole==3){
            $query = $this->db->query("SELECT quotationnr, company, quotationnr_prefix, (SELECT name FROM tblqualitycheckstatus WHERE id=quotationstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblquotations WHERE userid='".get_user_id()."' AND quotationstatus ".$condition." '".$status."' ORDER BY quotationnr DESC ".$limit);
        }else{
            $query = $this->db->query("SELECT quotationnr, company, quotationnr_prefix, (SELECT name FROM tblqualitycheckstatus WHERE id=quotationstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblquotations WHERE quotationstatus ".$condition." '".$status."' ORDER BY quotationnr DESC ".$limit);        
        }
        
        return $query->result_array();
    }
    
    public function LastPoints($limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
        $query = $this->db->query("SELECT slipnr, period, slipnr_prefix, pointsvalue FROM tblcommisionslips WHERE userid='".get_user_id()."' ORDER BY slipnr DESC ".$limit);        
        return $query->result_array();
    }
    
    public function LastQualityChecks($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
                
        //- On the Dashboard please add a new box "Quality-Check". Listed there all open Quality check. (Supporter)
        if($GLOBALS['current_user']->userrole==5){
            $query = $this->db->query("SELECT qualitychecknr, company, qualitychecknr_prefix, (SELECT name FROM tblqualitycheckstatus WHERE id=qualitycheckstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblqualitychecks WHERE qualitycheckstatus ".$condition." '".$status."' ORDER BY qualitychecknr DESC ".$limit);        
            return $query->result_array();
        }    
        //}else{            
        //}    
        
    }
    
    public function LastGoogleEvents($status, $condition, $limit=''){

        $crm_event_ids = array();
        $calendarColorCode = '';
        
        //Listed the next comming 5 Event from the calender of the Salesman.
        if($GLOBALS['current_user']->userrole==3){
            if($GLOBALS['current_user']->googleCalendarIDs){                
                require FCPATH.'googleapi/vendor/autoload.php';
                
                $system_colors = $this->Event_model->getSystemEventColor();
                $system_calendar_colors = $this->Event_model->getSystemCalendarColor();
        
                $client = new Google_Client();
                $client->setApplicationName('Optimus CRM');
                $client->setScopes(Google_Service_Calendar::CALENDAR);
                $client->setAuthConfig(FCPATH.'googleapi/client_secret.json');
                $client->setAccessType('offline');
                $client->setPrompt('select_account consent');

                // Load previously authorized token from a file, if it exists.
                $tokenPath = FCPATH.'googleapi/token.json';
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
                $service = new Google_Service_Calendar($client);
                
                //Check This is exist in Google
                $calendarId = $GLOBALS['current_user']->googleCalendarIDs;
                $calendarListEntry = $service->calendarList->get($calendarId);  
                $calendarColorCode = $system_calendar_colors[$calendarListEntry->colorId];
                
                if($calendarListEntry->getSummary()){

                    $optParams = array(
                        'orderBy' => 'startTime',
                        'singleEvents' => true,
                        'maxResults' => $limit,
                        'timeMin' => date('Y-m-d').'T00:00:00-04:00'                    
                    );

                    $results = $service->events->listEvents($calendarId, $optParams);
                    $events = $results->getItems();

                    if(isset($events) && count($events)){
                        foreach($events as $event){
                            //Check in CRM Exists
                            $crm_event = $this->Event_model->get("","",array()," google_eid='".$event->id."' ");
                            if(!$crm_event && count($crm_event)<=0){                                    
                                $event->colorId = ($event->colorId>0)?$event->colorId:'';

                                if(!empty($event->colorId)){
                                    $colorCode = $system_colors[$event->colorId];
                                }
                                else{
                                    $colorCode = $system_calendar_colors[$calendarListEntry->colorId];
                                }

                                if(!empty($event->start->dateTime) && !empty($event->end->dateTime)){
                                    //$event_calendar[] = array('calendarId'=>$calendarId,'google_eid'=>$event->id,'title'=>stripslashes($event->summary),'start'=>$event->start->dateTime,'end'=>$event->end->dateTime, 'color'=>$colorCode, 'htmlLink'=>$event->htmlLink);                                    

                                    //Add Sync Event in DB
                                    $event_start_dateTime = str_replace("T"," ",$event->start->dateTime);
                                    $event_end_dateTime = str_replace("T"," ",$event->end->dateTime);                                         
                                    //$event_end_dateTime = date("Y-m-d H:i:s",strtotime("-1 day", strtotime($event_end_dateTime)));

                                    //Get Lat and Lang 
                                    $event_location = '';
                                    $event_latitude = '';
                                    $event_longitude = '';
                                    if(isset($event->location) && $event->location!=""){
                                        $event_location = $event->location;
                                        $prepAddr = str_replace(' ','+',$event->location);
                                        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.get_option('google_api_key').'&address='.$prepAddr.'&sensor=false');
                                        $output= json_decode($geocode);
                                        $event_latitude = $output->results[0]->geometry->location->lat;
                                        $event_longitude = $output->results[0]->geometry->location->lng;
                                    }

                                    $dEvent = array(
                                        'userid' => 1,
                                        'public' => 1,

                                        'google_eid' => $event->id,
                                        'calendarId' => $calendarId,
                                        'google_htmllink' => $event->htmlLink,

                                        'title' => ($event->summary),
                                        'description' => ($event->description),

                                        'eventstatus' => 1, //Vorort Termin

                                        'event_startaddress' => $event_location,
                                        'event_startaddress_lat' => $event_latitude,
                                        'event_startaddress_lng' => $event_longitude,

                                        /*'event_address' => $event->location,
                                        'event_address_lat' => '',
                                        'event_address_lng' => '',*/

                                        'start' => $event_start_dateTime,
                                        'end' => $event_end_dateTime,

                                        'google_color_id'=>$event->colorId,
                                        'color'=>$colorCode
                                    );

                                    $eventid = $this->Event_model->add($dEvent,true);
                                    $crm_event_ids[] = $eventid;
                                    /*$single_event = (array) $this->get($eventid);
                                    if($single_event){
                                        $event_calendar[] = array('calendarId'=>$single_event['calendarId'],'id'=>$single_event['eventid'],'title'=>($single_event['title']),'start'=>str_replace(" ","T",$single_event['start']),'end'=>str_replace(" ","T",$single_event['end']), 'color'=>$single_event['color']);
                                    }*/    
                                }
                                else{
                                    //$event_calendar[] = array('calendarId'=>$calendarId,'google_eid'=>$event->id,'title'=>stripslashes($event->summary),'start'=>$event->start->date.'T00:00:00','end'=>$event->end->date.'T00:00:00', 'color'=>$colorCode, 'htmlLink'=>$event->htmlLink);                                    

                                    //Add Sync Event in DB
                                    $event_start_dateTime = str_replace("T"," ",$event->start->date.'T00:00:00');
                                    $event_end_dateTime = str_replace("T"," ",$event->end->date.'T00:00:00');
                                    //$event_end_dateTime = date("Y-m-d H:i:s",strtotime("-1 day", strtotime($event_end_dateTime)));

                                    //Get Lat and Lang 
                                    $event_location = '';
                                    $event_latitude = '';
                                    $event_longitude = '';
                                    if(isset($event->location) && $event->location!=""){
                                        $event_location = $event->location;
                                        $prepAddr = str_replace(' ','+',$event->location);
                                        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.get_option('google_api_key').'&address='.$prepAddr.'&sensor=false');
                                        $output= json_decode($geocode);
                                        $event_latitude = $output->results[0]->geometry->location->lat;
                                        $event_longitude = $output->results[0]->geometry->location->lng;
                                    }

                                    $dEvent = array(
                                        'userid' => 1,
                                        'public' => 1,

                                        'google_eid' => $event->id,
                                        'calendarId' => $calendarId,
                                        'google_htmllink' => $event->htmlLink,

                                        'title' => ($event->summary),
                                        'description' => ($event->description),

                                        'eventstatus' => 1, //Vorort Termin

                                        'event_startaddress' => $event_location,
                                        'event_startaddress_lat' => $event_latitude,
                                        'event_startaddress_lng' => $event_longitude,

                                        /*'event_address' => $event->location,
                                        'event_address_lat' => '',
                                        'event_address_lng' => '',*/

                                        'start' => $event_start_dateTime,
                                        'end' => $event_end_dateTime,

                                        'google_color_id'=>$event->colorId,
                                        'color'=>$colorCode
                                    );

                                    $eventid = $this->Event_model->add($dEvent,true);
                                    $crm_event_ids[] = $eventid;
                                    /*$single_event = (array) $this->get($eventid);
                                    if($single_event){
                                        $event_calendar[] = array('calendarId'=>$single_event['calendarId'],'id'=>$single_event['eventid'],'title'=>($single_event['title']),'start'=>str_replace(" ","T",$single_event['start']),'end'=>str_replace(" ","T",$single_event['end']), 'color'=>$single_event['color']);
                                    }*/   
                                }
                            }    
                            else{
                                //Update
                                $eventid = $crm_event[0]['eventid'];
                                $event->colorId = ($event->colorId>0)?$event->colorId:'';

                                if(!empty($event->colorId)){
                                    $colorCode = $system_colors[$event->colorId];
                                }
                                else{
                                    $colorCode = $system_calendar_colors[$calendarListEntry->colorId];
                                }

                                if(!empty($event->start->dateTime) && !empty($event->end->dateTime)){
                                    //$event_calendar[] = array('calendarId'=>$calendarId,'google_eid'=>$event->id,'title'=>stripslashes($event->summary),'start'=>$event->start->dateTime,'end'=>$event->end->dateTime, 'color'=>$colorCode, 'htmlLink'=>$event->htmlLink);                                    

                                    //Add Sync Event in DB
                                    $event_start_dateTime = str_replace("T"," ",$event->start->dateTime);
                                    $event_end_dateTime = str_replace("T"," ",$event->end->dateTime);   
                                    //$event_end_dateTime = date("Y-m-d H:i:s",strtotime("-1 day", strtotime($event_end_dateTime)));

                                    //Get Lat and Lang 
                                    $event_location = '';
                                    $event_latitude = '';
                                    $event_longitude = '';
                                    if(isset($event->location) && $event->location!=""){
                                        $event_location = $event->location;
                                        $prepAddr = str_replace(' ','+',$event->location);
                                        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.get_option('google_api_key').'&address='.$prepAddr.'&sensor=false');
                                        $output= json_decode($geocode);
                                        $event_latitude = $output->results[0]->geometry->location->lat;
                                        $event_longitude = $output->results[0]->geometry->location->lng;
                                    }

                                    $dEvent = array(
                                        /*'userid' => 1,
                                        'public' => 1,*/

                                        'google_eid' => $event->id,
                                        'calendarId' => $calendarId,
                                        'google_htmllink' => $event->htmlLink,

                                        'title' => ($event->summary),
                                        'description' => ($event->description),

                                        //'eventstatus' => 1, //Vorort Termin

                                        'event_startaddress' => $event_location,
                                        'event_startaddress_lat' => $event_latitude,
                                        'event_startaddress_lng' => $event_longitude,

                                        /*'event_address' => $event->location,
                                        'event_address_lat' => $latitude,
                                        'event_address_lng' => $longitude,*/

                                        'start' => $event_start_dateTime,
                                        'end' => $event_end_dateTime,

                                        'google_color_id'=>$event->colorId,
                                        'color'=>$colorCode
                                    );

                                    $eventid = $this->Event_model->update($dEvent, $eventid, 'added');
                                    $crm_event_ids[] = $eventid;
                                    /*$single_event = (array) $this->get($eventid);
                                    if($single_event){
                                        $event_calendar[] = array('calendarId'=>$single_event['calendarId'],'id'=>$single_event['eventid'],'title'=>($single_event['title']),'start'=>str_replace(" ","T",$single_event['start']),'end'=>str_replace(" ","T",$single_event['end']), 'color'=>$single_event['color']);
                                    }*/  
                                }
                                else{
                                    //$event_calendar[] = array('calendarId'=>$calendarId,'google_eid'=>$event->id,'title'=>stripslashes($event->summary),'start'=>$event->start->date.'T00:00:00','end'=>$event->end->date.'T00:00:00', 'color'=>$colorCode, 'htmlLink'=>$event->htmlLink);                                    

                                    //Add Sync Event in DB
                                    $event_start_dateTime = str_replace("T"," ",$event->start->date.'T00:00:00');
                                    $event_end_dateTime = str_replace("T"," ",$event->end->date.'T00:00:00');   
                                    //$event_end_dateTime = date("Y-m-d H:i:s",strtotime("-1 day", strtotime($event_end_dateTime)));

                                    //Get Lat and Lang 
                                    $event_location = '';
                                    $event_latitude = '';
                                    $event_longitude = '';
                                    if(isset($event->location) && $event->location!=""){
                                        $event_location = $event->location;
                                        $prepAddr = str_replace(' ','+',$event->location);
                                        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.get_option('google_api_key').'&address='.$prepAddr.'&sensor=false');
                                        $output= json_decode($geocode);
                                        $event_latitude = $output->results[0]->geometry->location->lat;
                                        $event_longitude = $output->results[0]->geometry->location->lng;
                                    }

                                    $dEvent = array(
                                        /*'userid' => 1,
                                        'public' => 1,*/

                                        'google_eid' => $event->id,
                                        'calendarId' => $calendarId,
                                        'google_htmllink' => $event->htmlLink,

                                        'title' => ($event->summary),
                                        'description' => ($event->description),

                                        //'eventstatus' => 1, //Vorort Termin

                                        'event_startaddress' => $event_location,
                                        'event_startaddress_lat' => $event_latitude,
                                        'event_startaddress_lng' => $event_longitude,

                                        /*'event_address' => $event->location,
                                        'event_address_lat' => '',
                                        'event_address_lng' => '',*/

                                        'start' => $event_start_dateTime,
                                        'end' => $event_end_dateTime,

                                        'google_color_id'=>$event->colorId,
                                        'color'=>$colorCode
                                    );

                                    $eventid = $this->Event_model->update($dEvent, $eventid, 'added');
                                    $crm_event_ids[] = $eventid;
                                    /*$single_event = (array) $this->get($eventid);
                                    if($single_event){
                                        $event_calendar[] = array('calendarId'=>$single_event['calendarId'],'id'=>$single_event['eventid'],'title'=>($single_event['title']),'start'=>str_replace(" ","T",$single_event['start']),'end'=>str_replace(" ","T",$single_event['end']), 'color'=>$single_event['color']);
                                    }*/   
                                }
                            }
                        }
                    }                            
                }            
            }
        }       
        
        if(is_array($crm_event_ids) && count($crm_event_ids)>0){
            $crm_event_ids = implode(",",$crm_event_ids);
        }else{
            $crm_event_ids = 0;
        }
        
        //Get 5 Events
        $events = $this->Event_model->get('',"tblevents.*, (SELECT name FROM tbleventstatus WHERE id=tblevents.eventstatus) as eventstatusname , DATE_FORMAT(`start`, '%Y-%m-%d') as sdate, DATE_FORMAT(`end`, '%Y-%m-%d') as edate ",array()," "
        . " (public=1 OR userid='".get_user_id()."') AND eventid IN(".$crm_event_ids.")  ");
        
        
        $data['calendarColorCode'] = $calendarColorCode;
        $data['events'] = $events;
        
        return $data;
    }
    
    public function LastMonitorings($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
        
        //- On the Dashboard he should only see Monitorings which belongs to the User who is logged in. (Customer)
        if(get_user_role()=='customer'){
            //$query = $this->db->query("SELECT monitoringnr, company, monitoringnr_prefix, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblmonitorings WHERE customer='".get_user_id()."' AND monitoringstatus ".$condition." '".$status."' ORDER BY monitoringnr DESC ".$limit);        
            $query = $this->db->query("SELECT monitoringnr, company, monitoringnr_prefix, (SELECT name FROM tblmonitoringstatus WHERE id=monitoringstatus) as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblmonitorings WHERE customer='".get_user_id()."' ORDER BY monitoringnr DESC ".$limit);        
            return $query->result_array();
        }
        
    }
    
    public function LastHardwareInvoices($status, $condition, $limit=''){
        if($limit!=""){ $limit = "LIMIT ".$limit; }
                
        //- On the Dashboard he should only see Hardware Assignments which belongs to the User who is logged in. (Customer)
        if(get_user_role()=='customer'){
            $query = $this->db->query("SELECT invoicenr, customer_company, invoicenr_prefix, is_paid as status, DATE_FORMAT(created,'%Y-%m-%d') as created FROM tblhardwareassignmentinvoices WHERE customer_id='".get_user_id()."' AND is_paid ".$condition." '".$status."' ORDER BY invoicenr DESC ".$limit);        
            return $query->result_array();
        }
    }
    
    public function LastNotifications($status, $condition, $limit=''){
        //$this->Reminder_model->get_user_reminders();
        if($limit!=""){ $limit = "LIMIT ".$limit; }
                
        //- on the Dashboard its better when we have a box with the last 5 Notifications.
        if(get_user_role()=='customer'){
            
            $query = $this->db->query("SELECT * FROM (

                SELECT `tblcustomerreminders`.`remindernr` as `id`, `tblcustomerreminders`.`rel_id`, `tblcustomerreminders`.`rel_type`, '".lang('page_customer')."' as type, `tblremindersubjects`.`name` as `subject`, `tblcustomerreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblcustomerreminders.reminddate, '%d.%m.%Y') as reminddate 
                FROM `tblcustomerreminders` LEFT JOIN `tblcustomers` ON `tblcustomers`.`customernr`=`tblcustomerreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblcustomerreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblcustomers`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblcustomerreminders`.`userid` 
                WHERE `tblcustomerreminders`.`rel_type` = 'customer' AND `tblcustomerreminders`.`reminderway` = 1 AND `tblcustomerreminders`.`isopen` =0 AND `tblcustomerreminders`.`isread` =0 AND `tblcustomerreminders`.`reminddate` <= NOW() AND `tblcustomers`.`responsible` = '2' 

            )  as reminders 
            
            ORDER BY reminddate DESC 

            ".$limit);               
            
        }else{
            $query = $this->db->query("SELECT * FROM (

            (SELECT `tblreminders`.`remindernr` as `id`, `tblreminders`.`rel_id`, `tblreminders`.`rel_type`, '".lang('page_lead')."' as type, `tblremindersubjects`.`name` as `subject`, `tblreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblreminders.reminddate, '%d.%m.%Y') as reminddate 
            FROM `tblreminders` LEFT JOIN `tblleads` ON `tblleads`.`leadnr`=`tblreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblleads`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblreminders`.`userid` 
            WHERE `tblreminders`.`rel_type` = 'lead' AND `tblreminders`.`reminderway` = 1 AND `tblreminders`.`isopen` =0 AND `tblreminders`.`isread` =0 AND `tblreminders`.`reminddate` <= NOW() AND `tblleads`.`responsible` = '".get_user_id()."')

            UNION ALL

            (SELECT `tbltodos`.`todonr` as `id`, `tbltodos`.`todonr` as `rel_id`, 'todo' as rel_type, '".lang('page_todo')."' as type, `tbltodos`.`todotitle` as `subject`, `tbltodos`.`tododesc` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tbltodos.reminderdate, '%d.%m.%Y') as reminddate 
            FROM `tbltodos` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tbltodos`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tbltodos`.`userid` 
            WHERE `tbltodos`.`reminderway` = 1 AND `tbltodos`.`isopen` =0 AND `tbltodos`.`isread` =0 AND `tbltodos`.`reminderdate` <= NOW() AND `tbltodos`.`responsible` = '".get_user_id()."')

            UNION ALL

            (SELECT `tblquotationreminders`.`remindernr` as `id`, `tblquotationreminders`.`rel_id`, `tblquotationreminders`.`rel_type`, '".lang('page_quotation')."' as type, `tblremindersubjects`.`name` as `subject`, `tblquotationreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblquotationreminders.reminddate, '%d.%m.%Y') as reminddate 
            FROM `tblquotationreminders` LEFT JOIN `tblquotations` ON `tblquotations`.`quotationnr`=`tblquotationreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblquotationreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblquotations`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblquotationreminders`.`userid` 
            WHERE `tblquotationreminders`.`rel_type` = 'quotation' AND `tblquotationreminders`.`reminderway` = 1 AND `tblquotationreminders`.`isopen` =0 AND `tblquotationreminders`.`isread` =0 AND `tblquotationreminders`.`reminddate` <= NOW() AND `tblquotations`.`responsible` = '".get_user_id()."')

            UNION ALL

            (SELECT `tblassignmentreminders`.`remindernr` as `id`, `tblassignmentreminders`.`rel_id`, `tblassignmentreminders`.`rel_type`, '".lang('page_assignment')."' as type, `tblremindersubjects`.`name` as `subject`, `tblassignmentreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblassignmentreminders.reminddate, '%d.%m.%Y') as reminddate 
            FROM `tblassignmentreminders` LEFT JOIN `tblassignments` ON `tblassignments`.`assignmentnr`=`tblassignmentreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblassignmentreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblassignments`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblassignmentreminders`.`userid` 
            WHERE `tblassignmentreminders`.`rel_type` = 'assignment' AND `tblassignmentreminders`.`reminderway` = 1 AND `tblassignmentreminders`.`isopen` =0 AND `tblassignmentreminders`.`isread` =0 AND `tblassignmentreminders`.`reminddate` <= NOW() AND `tblassignments`.`responsible` = '".get_user_id()."')

            UNION ALL

            (SELECT `tblhardwareassignmentreminders`.`remindernr` as `id`, `tblhardwareassignmentreminders`.`rel_id`, `tblhardwareassignmentreminders`.`rel_type`, '".lang('page_hardwareassignment')."' as type, `tblremindersubjects`.`name` as `subject`, `tblhardwareassignmentreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblhardwareassignmentreminders.reminddate, '%d.%m.%Y') as reminddate 
            FROM `tblhardwareassignmentreminders` LEFT JOIN `tblhardwareassignments` ON `tblhardwareassignments`.`hardwareassignmentnr`=`tblhardwareassignmentreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblhardwareassignmentreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblhardwareassignments`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblhardwareassignmentreminders`.`userid` 
            WHERE `tblhardwareassignmentreminders`.`rel_type` = 'hardwareassignment' AND `tblhardwareassignmentreminders`.`reminderway` = 1 AND `tblhardwareassignmentreminders`.`isopen` =0 AND `tblhardwareassignmentreminders`.`isread` =0 AND `tblhardwareassignmentreminders`.`reminddate` <= NOW() AND `tblhardwareassignments`.`responsible` = '".get_user_id()."')

            )  as reminders 
            
            ORDER BY reminddate DESC 

            ".$limit);        
        }
        
        return $query->result_array();
    }
}
