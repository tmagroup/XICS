    <?php
defined('BASEPATH') OR exit('No direct script access allowed');
// class Cronjobs extends CI_Controller
class Cronjobs extends MY2_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Employeecommission_model');
        $this->load->model('Todo_model');
        $this->load->model('Lead_model');
        $this->load->model('Customer_model');
        $this->load->model('Quotation_model');
        $this->load->model('Assignment_model');
        $this->load->model('Hardwareassignment_model');
        $this->load->model('Monitoring_model');
        $this->load->model('Event_model');
        $this->load->model('Qualitycheck_model');
    }

    public function view_mail()
    {
        $hostname = '{imap.dk-deutschland.de:993/imap/ssl/novalidate-cert}INBOX';
        $username = 'lead@dk-deutschland.de';
        $password = 'a3Gi8Efu';

        $date = date("Y-m-d");
        $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to server: ' . imap_last_error());
        $emails = imap_search($inbox,'SUBJECT "Neuer Termin" UNDELETED ON "'.$date.'"');

        if($emails) {
            $output = '';
            // rsort($emails);
            $dataNew = array();
            foreach($emails as $k => $email_number) {
                $overview = imap_fetch_overview($inbox,$email_number,0);
                $structure = imap_fetchstructure($inbox, $email_number);
                if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
                    $part = $structure->parts[0];
                    $message = imap_fetchbody($inbox,$email_number,2);

                    if($part->encoding == 3) {
                        $message = imap_base64($message);
                    } else if($part->encoding == 1) {
                        $message = imap_8bit($message);
                    } else {
                        $message = imap_qprint($message);
                    }
                }
                $htmlDom = new DOMDocument;
                @$htmlDom->loadHTML($message);
                $spanTags = $htmlDom->getElementsByTagName('span');

                foreach ($spanTags as $key => $value) {
                    if($value->getElementsByTagName('br')->length) {
                        foreach ($value->childNodes as $key => $val) {
                            if($val->nodeValue != '' && explode(':', $val->nodeValue)) {
                                $dataNew[$k][trim(explode(':', $val->nodeValue)[0])] = trim(explode(':', $val->nodeValue)[1]);
                                $dataNew[$k]['udate'] = $$overview[0]->udate;
                            }
                        }
                    } else {
                        if($value->nodeValue != '' && explode(':', $value->nodeValue)) {
                            $dataNew[$k][trim(explode(':', $value->nodeValue)[0])] = trim(explode(':', $value->nodeValue)[1]);
                           $dataNew[$k]['udate'] = $overview[0]->udate;
                        }
                    }
                }
            }
            if(!empty($dataNew)){
                foreach ($dataNew as $key => $value) {
                    $leadstatus = $this->db->select('*')->where('LOWER(name)', trim(strtolower($value['Leadstatus'])))->get('tblleadstatus')->row_array();
                    $appointment_type = $this->db->select('*')->where('LOWER(name)', trim(strtolower($value['Terminart'])))->get('tblappointmenttype')->row_array();
                    $provider = $this->db->select('*')->where('LOWER(name)', trim(strtolower($value['Provider'])))->get('tblprovider')->row_array();
                    $responsive_user = $this->db->select('*')->where('LOWER(username)', trim(strtolower($value['ResponsiveUser'])))->get('tblusers')->row_array();

                    $dataTermination = array(
                        'lead_status' => !empty($leadstatus) ? $leadstatus['id'] :0,
                        'appointment_type' => !empty($appointment_type) ? $appointment_type['id'] :0,
                        'provider' => !empty($provider) ? $provider['id'] :0,
                        'responsive_user' => !empty($responsive_user) ? $responsive_user['userid'] :0,
                        'salutation' => $value['Salution'] == 'Herr' ? '1' :'2',
                        'surname' => $value['Surname'],
                        'company_name' => $value['Company'],
                        'name' => $value['Name'],
                        'phone_number' => $value['Phone'],
                        'email' => $value['Email'],
                        'cards' => $value['Cards'],
                        'date' => date('Y-m-d'),
                        'employment' => $value['Employment'],
                        'street' => $value['Street'],
                        'zipcode' => $value['Zipcode'],
                        'city' => $value['City'],
                        'notice' => $value['Notice'],
                        'mail_date' => $value['udate'],
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    $exists = $this->db->select('id')->where('mail_date',$value['udate'])->get('tbltermination')->row_array();
                    if(empty($exists)) {
                        $this->db->insert('tbltermination', $dataTermination);
                        $insert_id = $this->db->insert_id();
                    }
                }
            }
        }
    }

    public function terminationAcceptCancel($id,$status)
    {
        $terminationData = $this->db->select('*')->where('md5(id)', $id)->get('tbltermination')->row_array();
        $data['terminationData']  = $terminationData;

        if($id != ''){
            if($status == 'accept') {
                $post['lead_status'] = '8';
                $post['status'] = '1';
                if($terminationData['status'] != '1') {
                    $this->addCalendarsEvent($id);
                }
                $this->load->view('admin/termination/accept', $data, FALSE);
            }

            if($status == 'cancel'){
                $post['lead_status'] = '9';
                $post['status'] = '2';
                $this->load->view('admin/termination/cancel', $data, FALSE);
            }
            $this->db->where('md5(id)', $id);
            $this->db->update('tbltermination', $post);
        }
    }

    public function addCalendarsEvent($id)
    {
        $data = $this->db
                     ->select('t.*,u.googleCalendarIDs')
                     ->from('tbltermination t')
                     ->join('tblusers u','u.userid=t.responsive_user')
                     ->where('md5(t.id)',$id)
                     ->get()
                     ->row_array();

        $startDate = date('Y-m-d',strtotime($data['date'])).' 00:01:00';
        $endDate = date('Y-m-d',strtotime($data['date'])).' 00:23:59';

        $dataEvent['created'] = date('Y-m-d H:i:s');
        $dataEvent['userid']  = $data['responsive_user'];
        // $dataEvent['userid']  = get_user_id();
        $dataEvent['start']   = $startDate;
        $dataEvent['end']     = $endDate;
        $dataEvent['public']  = 0;

        $this->db->insert('tblevents', $dataEvent);
        $event_id = $this->db->insert_id();

        if($event_id > 0) {
            $calendarId = explode(',', $data['googleCalendarIDs']);

            $dataNew  = array(
                'calendarId' => $calendarId[0],
                'start' => $startDate,
                'end' => $endDate,
                'title' => $data['name'],
                'google_color_id' => '2',
                'event_address' => $data['street'].','.$data['city'],
                'description' => $data['notice'],
            );
            $this->Event_model->addGoogleEvent($dataNew,$event_id);
        }
    }
    //once a day check if 1st day of month at morning
    public function employeecommissions_generateslip($month_year = ''){
        if(date('d')=='01'){
            //If Not Pass Param Month-Year It will set default
            if($month_year==''){
                $month_year = date('Y-m',strtotime("-1 month"));
            }
            $this->Employeecommission_model->create_employeecommissionslip($month_year);
        }
    }

    //once a day at morning
    public function todos_generatereminder($id=''){
        //Reminder to Responsible, Teamwork
        $this->Todo_model->sendReminder($id);
    }

    //- Reminder-Email: become 1 day before and 30 Min before set time
    //once a day at morning
    public function leads_generatereminder($id=''){
        //Reminder to Responsible, Teamwork
        $this->Lead_model->sendReminder($id);
    }

    //once a day at morning
    public function customers_generatereminder($id=''){
        //Reminder to Responsible, Teamwork
        $this->Customer_model->sendReminder($id);
    }

    //once a day at morning
    public function quotations_generatereminder($id=''){
        //Reminder to Responsible, Teamwork
        $this->Quotation_model->sendReminder($id);
    }

    //once a day at morning
    public function quotations_generatereminder_quotation($id=''){
        //Reminder to Responsible, Teamwork
        $this->Quotation_model->sendReminder_quotation($id);
    }

    //once a day at morning
    public function assignments_generatereminder($id=''){
        //Reminder to Responsible, Teamwork
        $this->Assignment_model->sendReminder($id);
    }

    //everday at 23.00 oclock.
    public function assignments_generatereminder_assignment($id=''){
        //Reminder to Responsible, Teamwork
        $this->Assignment_model->sendReminder_assignment($id);
    }

    //once a day at morning
    public function hardwareassignments_generatereminder($id=''){
        //Reminder to Responsible, Teamwork
        $this->Hardwareassignment_model->sendReminder($id);
    }

    //once a day at morning
    public function todos_generatetodo(){
        $dataCustomers = $this->Customer_model->get('','',array()," is_new_contact=0 AND (datediff(NOW(), lastcontact))>90 ");
        if(isset($dataCustomers) && count($dataCustomers)>0){
            foreach($dataCustomers as $dataCustomer){
                $post = array(
                    'todotitle' => 'Kontakt zum Kunden',
                    /*'company' => $dataCustomer['company'],*/
                    'startdate' => _d(date('Y-m-d')),
                    'todostatus' => 1, //Erstellt
                    'customer' => $dataCustomer['customernr'],
                    'responsible' => $dataCustomer['responsible'],
                    'tododesc' => 'Sie haben diesen Kunden vor 3 Monaten zuletzt kontaktiert. Bitte kontaktieren Sie Ihren Kunden um die Zufreidenheit sicherzustellen.',
                    'reminderdate' => _d(date("Y-m-d",strtotime("2 day")))
                );
                $this->Todo_model->add($post);

                //Update is new contact to customer
                $this->db->query("UPDATE `tblcustomers` SET `is_new_contact` = 1 WHERE  `customernr` = '".$dataCustomer['customernr']."' ");
            }
        }
    }

    //once a day at morning
    public function monitorings_generatemonitoringjob($sync = ''){
        /*if(date('d')=='10'){
            $dataCustomers = $this->Customer_model->get('','',array()," monitoring=1 ");
            if(isset($dataCustomers) && count($dataCustomers)>0){
                foreach($dataCustomers as $dataCustomer){
                    $dataMonitoring = $this->Monitoring_model->get("","",array()," customer='".$dataCustomer['customernr']."' ");

                    if(!$dataMonitoring){
                        $post = array(
                            'customer' => $dataCustomer['customernr'],
                            'responsible' => $dataCustomer['responsible'],
                            'date' => _d(date("Y-m-d")),
                            'monitoringstatus' => 1, //Erstellt
                            'company' => $dataCustomer['company'],
                            'monitoringlink' => $dataCustomer['monitoringlink'],
                            'monitoringuser' => $dataCustomer['monitoringuser'],
                            'monitoringpass' => $dataCustomer['monitoringpass'],
                            'monitoringvalue' => $dataCustomer['monitoringvalue']
                        );
                        $this->Monitoring_model->add($post);
                    }
                }
            }
        }*/

        if(date('d')=='20' || $sync == 'YES'){
            $dataCustomers = $this->Customer_model->get('','tblcustomers.*, tblassignments.assignmentnr',array('tblassignments'=>'tblassignments.customer=tblcustomers.customernr')," tblcustomers.monitoring=1 ");
            if(isset($dataCustomers) && count($dataCustomers)>0){
                foreach($dataCustomers as $k => $dataCustomer){
                    $dataMonitoring = $this->Monitoring_model->get("","",array()," assignmentnr='".$dataCustomer['assignmentnr']."' AND MONTH(date)='".date('m')."' AND YEAR(date)='".date('Y')."'");
                    if(empty($dataMonitoring) && $dataCustomer['assignmentnr'] != ''){
                        $post = array(
                            'customer' => $dataCustomer['customernr'],
                            'responsible' => $dataCustomer['responsible'],
                            'date' => date("Y-m-d"),
                            'monitoringstatus' => 1, //Erstellt
                            'company' => $dataCustomer['company'],
                            'monitoringlink' => $dataCustomer['monitoringlink'],
                            'monitoringuser' => $dataCustomer['monitoringuser'],
                            'monitoringpass' => $dataCustomer['monitoringpass'],
                            'monitoringvalue' => $dataCustomer['monitoringvalue'],
                            'assignmentnr' => $dataCustomer['assignmentnr']
                        );
                        $this->Monitoring_model->add($post);
                    }
                }
            }

            if($sync == 'YES') {
                redirect(site_url('admin/monitorings'));
            }
        }
    }

    //once a day at morning
    public function calendars_generatequalitycheck(){
        $events = $this->Event_model->get("","eventid",array(),"eventstatus=1 AND DATEDIFF(DATE_FORMAT(`start`,'%Y-%m-%d'),'".date('Y-m-d')."')='-1' ");
        foreach($events as $event){
            $this->calendar_saveQualitycheck($event['eventid']);
        }
    }
    function calendar_saveQualitycheck($eventid){
        //Get Assignmentnr
        $rowEvent = (array) $this->Event_model->get($eventid,"assignmentnr, proofuser");
        $assignmentnr = $rowEvent['assignmentnr'];
        //$proofuser = $rowEvent['proofuser'];

        //Get Detail of Assignment
        $rowAssignment = (array) $this->Assignment_model->get($assignmentnr);
        $data = $rowAssignment;

        //Date of generating
        $qualitycheckstart = date('Y-m-d');
        $data_qualitycheck = array(
            'qualityissue' => 'Termin nicht wahrgenommen',
            //'assignmentnr' => $assignmentnr,
            'rel_id' => $eventid,
            'rel_type' => 'event',
            'qualitycheckstart' => $qualitycheckstart,
            'company' => @$data['company'],
            'responsible' => @$data['responsible'],
            /*'proofuser' => $proofuser,*/
            'qualitycheckstatus' => 1, //Often
            'question1' => 4
        );
        $qualitychecknr = $this->Qualitycheck_model->add($data_qualitycheck,'hardwarequalitycheck');
    }

    public function terminationEmailSend()
    {
        $terminationData = $this->db
                                ->select('tt.*')
                                ->from('tbltermination tt')
                                ->where('status','0')
                                ->get()
                                ->result_array();

        if(!empty($terminationData)) {
            foreach ($terminationData as $key => $data) {
                $cron_send_time = strtotime('+24 hours',strtotime($data['mail_send_time']));
                $currentime = time();

                if($cron_send_time < $currentime) {
                    if($data['provider'] == '2') {
                        $emailtemplate = 'terminationtelekon';
                        $logo_image_url = base_url().'assets/telekon.png';
                    } else if($data['provider'] == '3') {
                        $emailtemplate = 'terminationO2Business';
                        $logo_image_url = base_url().'assets/o2.png';
                    } else {
                        $emailtemplate = 'terminationvodafone';
                        $logo_image_url = base_url().'assets/Vodafone.jpg';
                    }

                    $mer_data['customer_surname'] = $data['surname'];
                    $mer_data['customer_name'] = $data['name'];
                    $mer_data['logo_image_url'] = $logo_image_url;
                    $mer_data['data_type'] = date('d-m-Y',strtotime($data['date']));
                    $mer_data['appoiment_date'] = date('d-m-Y',strtotime($data['date']));
                    $mer_data['accept_url'] = base_url().'admin/termination/terminationAcceptCancel/'.$id.'/accept/';
                    $mer_data['cancel_url'] = base_url().'admin/termination/terminationAcceptCancel/'.$id.'/cancel/';

                    $merge_fields = array();
                    $merge_fields = array_merge($merge_fields, get_customertermination_merge_fields($mer_data));

                    $sent = $this->Email_model->send_email_template($emailtemplate, $data['email'], $merge_fields);

                    if($sent) {
                        $current_datetime = date('Y-m-d H:i:s');
                        $this->update_record($this->table,array('id'=>$id),array('status' => '3','mail_send_time'=> $current_datetime));
                    }
                }
            }
        }

    }
}
