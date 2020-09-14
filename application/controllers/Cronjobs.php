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
}
