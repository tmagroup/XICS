<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Event_model extends CI_Model
{
    var $table = 'tblevents';
    var $aid = 'eventid';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Assignment_model');
        $this->load->model('Qualitycheck_model');
        ini_set('display_errors', 0);
        // ini_set('allow_url_fopen', 1);
    }

    /**
     * Check if Event
     * @return mixed
     */
    public function get($id='', $field='', $join=array(), $where="", $groupby="")
    {
        //Select Fields
        if($field!=""){
            $this->db->select($field);
        }

        //Join
        if(count($join)>0){
            foreach ($join as $key=>$value){
                $this->db->join($key, $value, 'left');
            }
        }

        //Group By
        if($groupby!=""){
            $this->db->group_by($groupby);
        }

        //Where
        if($where!=""){
            $this->db->where($where);
        }

        if (is_numeric($id)) {
            $this->db->where($this->table.".".$this->aid, $id);
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new Event
     * @param array $data Event $_POST data
     */
    public function add($data, $isGoogle=false)
    {
        ini_set('display_errors', 0);
        ini_set('allow_url_fopen', 1);

        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        $data['start'] = to_sql_date($data['start'], true);
        $data['end'] = to_sql_date($data['end'], true);
        $data['public'] = isset($data['public'])?1:0;

        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        if($id>0){

            //Add Event in Google Calendar Account
            if(!$isGoogle){
                @$this->addGoogleEvent($data, $id);
            }

            //Add Qualitycheck if status is "Termin wahrgenommen"
            //When status will changed to "Termin abgesagt" so generate a Qualitycheck with Prüfzweck "Terminabsage".
            if($data['eventstatus']==2 || $data['eventstatus']==4){
                $this->saveQualitycheck($id);
            }

            //Log Activity
            logActivity('New Event Added [ID: ' . $id . ']');
        }

        return $id;
    }

    /**
     * Update Event
     * @param  array $data Event
     * @param  mixed $id   Event id
     * @return boolean
     */
    public function update($data, $id, $flag='', $debugP='')
    {
        //Get Old Event Status
        $rowEvent = (array) $this->get($id,"eventstatus");
        $old_eventstatus = $rowEvent['eventstatus'];

        //Database data
        if(isset($data['start'])){
            $data['start'] = to_sql_date($data['start'], true);
        }
        if(isset($data['end'])){
            $data['end'] = to_sql_date($data['end'], true);
        }

        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);


        //Update Event in Google Calendar Account
        if($flag==""){
            if(isset($data['google_eid']) && $data['google_eid']!=""){
                @$this->updateGoogleEvent($data, $data['google_eid']);
            }
        }

        //Add Qualitycheck if status is "Termin wahrgenommen"
        //When status will changed to "Termin abgesagt" so generate a Qualitycheck with Prüfzweck "Terminabsage".

        if (isset($data['eventstatus']) && !empty($data['eventstatus'])) {
            if($old_eventstatus!=$data['eventstatus'] && ($data['eventstatus']==2 || $data['eventstatus']==4)){
                $this->saveQualitycheck($id);
            }
        }

        if ($this->db->affected_rows() > 0) {
            $this->db->query("UPDATE ".$this->table." SET `updated`='".date('Y-m-d H:i:s')."' WHERE ".$this->aid."='".$id."' ");

            //Log Activity
            logActivity('Event Updated [ID: ' . $id . ']');
        }

        return $id;
    }


    function saveQualitycheck($eventid){

        //Get Assignmentnr
        $rowEvent = (array) $this->get($eventid,"assignmentnr, proofuser, eventstatus");
        $assignmentnr = $rowEvent['assignmentnr'];
        if($rowEvent['eventstatus']==2){
            $qualityissue = 'Termincheck';
        }else if($rowEvent['eventstatus']==4){
            $qualityissue = 'Terminabsage';
        }
        //$proofuser = $rowEvent['proofuser'];

        //Get Detail of Assignment
        // $rowAssignment = (array) $this->Assignment_model->get($assignmentnr);
        $rowAssignment = (array) $this->Customer_model->get($assignmentnr);
        $data = $rowAssignment;

        $data['assignmentdate'] = (isset($data['assignmentdate']) && !empty($data['assignmentdate'])) ? $data['assignmentdate'] : 0;

        //Date of generating Assignmentdate+1 Days
        $qualitycheckstart = date('Y-m-d', strtotime('+1 days', strtotime($data['assignmentdate'])));
        if(isset($assignmentnr) && $assignmentnr>0){
            //Quality Check
            $em = $this->Qualitycheck_model->get('','qualitychecknr',array()," rel_id='".$eventid."' ");
            if(isset($em) && count($em)>0){
                $data_qualitycheck = array(
                    'qualityissue' => $qualityissue,
                    'rel_id' => $eventid,
                    'rel_type' => 'event',
                    'qualitycheckstart' => $qualitycheckstart,
                    'company' => $data['company'],
                    'responsible' => $data['responsible'],
                    /*'proofuser' => $proofuser,*/
                    'qualitycheckstatus' => 1, //Often
                    'question1' => 4
                );
                $qualitychecknr = $this->Qualitycheck_model->add($data_qualitycheck,'hardwarequalitycheck');
            }
            else{
                $data_qualitycheck = array(
                    'qualityissue' => $qualityissue,
                    'rel_id' => $eventid,
                    'rel_type' => 'event',
                    'qualitycheckstart' => $qualitycheckstart,
                    'company' => $data['company'],
                    'responsible' => $data['responsible'],
                    /*'proofuser' => $proofuser,*/
                    'qualitycheckstatus' => 1, //Often
                    'question1' => 4
                );
                $qualitychecknr = $this->Qualitycheck_model->add($data_qualitycheck,'hardwarequalitycheck');
            }
        }
    }

    /**
     * Delete Event
     * @param  array $data Event
     * @param  mixed $id   Event id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Eventid
        $rowfield = $this->get($id,'google_eid,calendarId');

        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Delete Event in Google Calendar Account
        @$this->deleteGoogleEvent($rowfield->google_eid, $rowfield->calendarId);

        //Log Activity
        logActivity('Event Deleted [ID: ' . $id .']');

        return 1;
    }

    /**
    * Get system system event colors
    * @return array
    */
    function getSystemEventColor()
    {
       // don't delete any of these colors are used all over the system
       $colors[1] = '#7b87c9';
       $colors[2] = '#02b67f';
       $colors[3] = '#9528a6';
       $colors[4] = '#eb7e75';
       $colors[5] = '#f8c142';
       $colors[6] = '#fa5527';
       $colors[7] = '#019ae1';
       $colors[8] = '#626262';
       $colors[9] = '#4452b2';
       $colors[10] = '#01814a';
       $colors[11] = '#db1202';

       return $colors;
    }


    /**
    * Get system system event colors
    * @return array
    */
    function getSystemCalendarColor()
    {
       // don't delete any of these colors are used all over the system
       $colors[1] = '#ac725e';
       $colors[2] = '#d06b64';
       $colors[3] = '#f83a22';
       $colors[4] = '#fa573c';
       $colors[5] = '#ff7537';
       $colors[6] = '#ffad46';
       $colors[7] =  '#42d692';
       $colors[8] = '#16a765';
       $colors[9] = '#7bd148';
       $colors[10] = '#b3dc6c';
       $colors[11] = '#fbe983';
       $colors[12] = '#fad165';
       $colors[13] = '#92e1c0';
       $colors[14] = '#9fe1e7';
       $colors[15] = '#9fc6e7';
       $colors[16] = '#4986e7';
       $colors[17] = '#9a9cff';
       $colors[18] = '#b99aff';
       $colors[19] = '#c2c2c2';
       $colors[20] = '#cabdbf';
       $colors[21] = '#cca6ac';
       $colors[22] = '#f691b2';
       $colors[23] = '#cd74e6';
       $colors[24] = '#a47ae2';

       return $colors;
    }


    /* Get Google Calendar Event Color */
    public function getGoogleEventColor(){
        require FCPATH.'googleapi/vendor/autoload.php';

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

        return $colors = $service->colors->get();
    }

    /* Add Google Calendar Event */
    public function addGoogleEvent($data, $id){
        if(!$data['calendarId']){ return ''; }

        $data['start'] = $this->my_date(date_create($data['start']), 'start');
        $data['end'] = $this->my_date(date_create($data['end']), 'end');

        require FCPATH.'googleapi/vendor/autoload.php';

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

            // echo "<pre>";
            // print_r($service);
            // die();

        $event = new Google_Service_Calendar_Event(array(
            'summary' => $data['title'],
            'colorId' => $data['google_color_id'],
            // 'location' => $data['event_startaddress'],
            'location' => $data['event_address'],
            'description' => $data['description'],
            'start' => array(
              // 'dateTime' => str_replace(" ","T",$data['start']).'-00:00',
              'dateTime' => str_replace(' ', 'T', $data['start']),
              'timeZone' => 'Europe/Berlin',
            ),
            'end' => array(
              // 'dateTime' => str_replace(" ","T",$data['end']).'-00:00',
              'dateTime' => str_replace(' ', 'T', $data['end']),
              'timeZone' => 'Europe/Berlin',
            ),
        ));


        //$calendarId = get_option('google_calendar_main_calendar');


        $calendarId = trim($data['calendarId']);//User's Calendar ID or Master Admin Select Calendar ID
        $event = $service->events->insert($calendarId, $event);
        //     echo "<pre>";
        // print_r($event);
        // die();
        $data_event = array('google_eid'=>$event->id,'google_htmllink'=>$event->htmlLink);
        $this->update($data_event, $id, 'added', 'd1');
    }

    /* Update Google Calendar Event */
    public function updateGoogleEvent($data, $google_eid){
        if(!$data['calendarId']){ return ''; }

        $data['start'] = $this->my_date(date_create($data['start']), 'start');
        $data['end'] = $this->my_date(date_create($data['end']), 'end');

        require FCPATH.'googleapi/vendor/autoload.php';

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

        $event = new Google_Service_Calendar_Event(array(
            'summary' => $data['title'],
            'colorId' => $data['google_color_id'],
            // 'location' => $data['event_startaddress'],
            'location' => $data['event_address'],
            'description' => $data['description'],
            'start' => array(
              // 'dateTime' => str_replace(" ","T",$data['start']).'-00:00',
              'dateTime' => str_replace(' ', 'T', $data['start']),
              'timeZone' => 'Europe/Berlin',
            ),
            'end' => array(
              // 'dateTime' => str_replace(" ","T",$data['end']).'-00:00',
              'dateTime' => str_replace(' ', 'T', $data['end']),
              'timeZone' => 'Europe/Berlin',
            ),
        ));

        //$calendarId = get_option('google_calendar_main_calendar');
        $calendarId = trim($data['calendarId']);//User's Calendar ID or Master Admin Selected Calendar ID
        $service->events->update($calendarId, $google_eid, $event);
    }

    /* Delete Google Calendar Event */
    public function deleteGoogleEvent($google_eid, $calendarId){
        if(!$calendarId){ return ''; }

        require FCPATH.'googleapi/vendor/autoload.php';

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
        //$calendarId = get_option('google_calendar_main_calendar');
        //$calendarId User's Calendar ID or Master Admin Selected Calendar ID
        $service->events->delete(trim($calendarId), $google_eid);

        return true;
    }

    /* Get Google Calendar Event */
    public function getGoogleEvents($start, $end){
        require FCPATH.'googleapi/vendor/autoload.php';

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

        //$calendarId = get_option('google_calendar_main_calendar');
        //User's Calendar ID or Master Admin Selected Calendar ID
        $event_calendar = array();
        $dataUser = $this->User_model->get('','googleCalendarIDs'," userid='".get_user_id()."' ");
        $calendarIds = explode(",",$dataUser[0]['googleCalendarIDs']);
        $system_colors = $this->getSystemEventColor();
        $system_calendar_colors = $this->getSystemCalendarColor();

        $crm_event_ids = array();
        if(isset($calendarIds) && count($calendarIds)>0)
        {
            foreach($calendarIds as $calendarId){
                if(isset($_POST['filter_googleCalendarIDs']) && count($_POST['filter_googleCalendarIDs'])>0){

                    if(in_array($calendarId,$_POST['filter_googleCalendarIDs'])){

                        //Check This is exist in Google
                        $calendarListEntry = $service->calendarList->get($calendarId);
                        if($calendarListEntry->getSummary()){
                            $optParams = array(
                            'orderBy' => 'startTime',
                            'singleEvents' => true,
                            'timeMin' => $start.'T00:00:00-04:00',
                            'timeMax' => $end.'T23:59:59-04:00'
                            );

                            $results = $service->events->listEvents($calendarId, $optParams);
                            $events = $results->getItems();

                            if(isset($events) && count($events)){
                                foreach($events as $event){
                                    //Check in CRM Exists
                                    $crm_event = $this->get("","",array()," google_eid='".$event->id."' ");
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
                                                'event_address_lat' => $latitude,
                                                'event_address_lng' => $longitude,*/

                                                'start' => $event_start_dateTime,
                                                'end' => $event_end_dateTime,

                                                'google_color_id'=>$event->colorId,
                                                'color'=>$colorCode
                                            );

                                            $eventid = $this->add($dEvent,true);
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

                                            $eventid = $this->add($dEvent,true);
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

                                            $eventid = $this->update($dEvent, $eventid, 'added', 'd2');
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

                                            $eventid = $this->update($dEvent, $eventid, 'added', 'd3');
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

                }else{

                    //Check This is exist in Google
                    $calendarListEntry = $service->calendarList->get($calendarId);
                    if($calendarListEntry->getSummary()){
                        $optParams = array(
                        'orderBy' => 'startTime',
                        'singleEvents' => true,
                        'timeMin' => $start.'T00:00:00-04:00',
                        'timeMax' => $end.'T23:59:59-04:00'
                        );

                        $results = $service->events->listEvents($calendarId, $optParams);
                        $events = $results->getItems();

                        if(isset($events) && count($events)){
                            foreach($events as $event){
                                //Check in CRM Exists
                                $crm_event = $this->get("","",array()," google_eid='".$event->id."' ");
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

                                        // $dEvent['start'] = $this->my_date(date_create($event_start_dateTime), 'start');
                                        // $dEvent['end'] = $this->my_date(date_create($event_end_dateTime), 'end');

                                        $eventid = $this->add($dEvent,true);
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

                                        // $dEvent['start'] = $this->my_date(date_create($event_start_dateTime), 'start');
                                        // $dEvent['end'] = $this->my_date(date_create($event_end_dateTime), 'end');

                                        $eventid = $this->add($dEvent,true);
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

                                            if (!empty($output->results[0])) {
                                                $event_latitude = $output->results[0]->geometry->location->lat;
                                                $event_longitude = $output->results[0]->geometry->location->lng;
                                            }
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

                                        // $dEvent['start'] = $this->my_date(date_create($event_start_dateTime), 'start');
                                        // $dEvent['end'] = $this->my_date(date_create($event_end_dateTime), 'end');

                                        // my_debug_file(array('$dEvent' => $dEvent), 'd4');

                                        $eventid = $this->update($dEvent, $eventid, 'added', 'd4');
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

                                        // $dEvent['start'] = $this->my_date(date_create($event_start_dateTime), 'start');
                                        // $dEvent['end'] = $this->my_date(date_create($event_end_dateTime), 'end');

                                        $eventid = $this->update($dEvent, $eventid, 'added', 'd5');
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
        }

        return $crm_event_ids;
    }

    /* Get Single Google Calendar Event */
    public function getSingleGoogleEvent($google_eid, $calendarId){
        if(!$calendarId){ return ''; }

        require FCPATH.'googleapi/vendor/autoload.php';

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
        //$calendarId = get_option('google_calendar_main_calendar');
        $event = $service->events->get($calendarId, $google_eid);
        $system_colors = $this->getSystemEventColor();
        $system_calendar_colors = $this->getSystemCalendarColor();

        //Check in CRM Exists
        $googleCalendarName = '';
        $event_calendar = array();
        $crm_event = $this->get("","",array()," google_eid='".$event->id."' ");
        if(!$crm_event && count($crm_event)<=0){
            //$event_calendar[] = array('google_eid'=>$event->id,'title'=>$event->summary,'start'=>$event->start->dateTime,'end'=>$event->end->dateTime, 'color'=>$system_colors[$event->colorId], 'htmlLink'=>$event->htmlLink);

            $calendarListEntry = $service->calendarList->get($calendarId);
            $googleCalendarName = $calendarListEntry->summary;
            if(!empty($event->colorId)){
                $colorCode = $system_colors[$event->colorId];
            }
            else{
                $colorCode = $system_calendar_colors[$calendarListEntry->colorId];
            }

            if(!empty($event->start->dateTime) && !empty($event->end->dateTime)){
                $event_start_dateTime = str_replace("T"," ",$event->start->dateTime);
                $event_end_dateTime = str_replace("T"," ",$event->end->dateTime);
                $event_start_dateTime = explode("+",$event_start_dateTime);
                $event_end_dateTime = explode("+",$event_end_dateTime);
                $event_start_dateTime = $event_start_dateTime[0];
                $event_end_dateTime = $event_end_dateTime[0];
            }
            else{
                $event_start_dateTime = $event->start->date;
                $event_end_dateTime = $event->end->date;
            }

            $event_calendar = array(
                'userid' => 0,
                'event_type' => 'GOOGLE_EVENT',
                'google_eid' => $event->id,
                'calendarId' => $calendarId,
                'google_htmllink' => $event->htmlLink,
                'title' => stripslashes($event->summary),
                'description' => stripslashes($event->description),
                'event_startaddress' => $event->location,
                'event_address' => $event->location,
                'start' => $event_start_dateTime,
                'end' => $event_end_dateTime,
                'color'=>$colorCode,
                'googleCalendarName'=>$googleCalendarName
            );
        }

        return $event_calendar;
    }



    public function getSingleGoogleEventDirect($calendar_id, $google_eid) {
        require FCPATH .'googleapi/vendor/autoload.php';
        $client = new Google_Client();
        $client->setApplicationName('Optimus CRM');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig(FCPATH .'googleapi/client_secret.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        // Load previously authorized token from a file, if it exists.
        $accessToken = json_decode(file_get_contents(FCPATH .'googleapi/token.json'), true);
        $client->setAccessToken($accessToken);
        $service = new Google_Service_Calendar($client);
        $event = $service->events->get($calendar_id, $google_eid);

        $return_event = array('responsive_to_name' => $event->organizer->displayName);
        return $return_event;
    }



    /* Get Google Calendars */
    public function getGoogleCalendarList(){
        require FCPATH.'googleapi/vendor/autoload.php';

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
        $calendarList = $service->calendarList->listCalendarList();

        $calendars = array();
        if(isset($calendarList) && count($calendarList)){
            foreach($calendarList as $calendar){
                if($calendar->accessRole!='owner'){ continue; }

                $primary = isset($calendar->primary)?$calendar->primary:0;
                if($primary==1){ $summary='Primary'; }else{ $summary=$calendar->summary; }

                $calendars[] = array(
                    'id' => $calendar->id,
                    'colorId' => $calendar->colorId,
                    'backgroundColor' => $calendar->backgroundColor,
                    'summary' => $summary,
                    'primary' => $primary
                );
            }
        }

        return $calendars;
    }

    /*function getDistance(array $location1, array $location2, $precision = 0, $useMiles = true){
        // Get the Earth's radius in miles or kilometers
        $radius = $useMiles ? 3955.00465 : 6364.963;
        // Convert latitude from degrees to radians
        $lat1 = deg2rad($location1[0]);
        $lat2 = deg2rad($location2[0]);
        // Get the difference between longitudes and convert to radians
        $long = deg2rad($location2[1] - $location1[1]);
        // Calculate the distance
        return round(acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($long)) * $radius, $precision);
    }*/

    /*function getDistance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
              return $miles;
            }
    }*/


    private function my_date($date, $debugP='') {
        // $timestamp = $date->getTimestamp() - 7200;
        $timestamp = $date->getTimestamp() - 0;
        return date('Y-m-d H:i:sP', $timestamp);
    }

}
