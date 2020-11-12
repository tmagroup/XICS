<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 */
class MY2_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->CI =& get_instance();

        // Get Current Date and Time from DB
        $row_sql_now = $this->db->query("SELECT NOW() AS sql_now")->row()->sql_now;

        $GLOBALS['current_datetime'] = $row_sql_now;
        $GLOBALS['current_user'] = (object) array(
            'email' => ''
        );
    }
}



class Admin_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->CI = & get_instance();

        $this->load->model('User_model');
        $this->load->model('Customer_model');
        $this->load->model('Authentication_model');
        $this->load->model('Currencies_model');


        //Get Current Date and Time from DB
        $rowCurrentDateTime = $this->db->query("SELECT NOW() as current_datetime");
        $GLOBALS['current_datetime'] = $rowCurrentDateTime->row()->current_datetime;


	//Autologin
        $this->Authentication_model->autologin();
        if (!is_logged_in()) {
            redirect(site_url());
        }

        // //Get Current Date and Time from DB
        // $rowCurrentDateTime = $this->db->query("SELECT NOW() as current_datetime");
        // $GLOBALS['current_datetime'] = $rowCurrentDateTime->row()->current_datetime;

        //Get (User/Customer)
        $user_role = get_user_role();

        if($user_role=='customer'){
            // Update customer last activity
            $this->db->where('customernr',get_user_id());
            $this->db->update('tblcustomers',array('last_activity'=>date('Y-m-d H:i:s')));

            // Deleted or inactive but have session
            $currentUser = $this->Customer_model->get(get_user_id());
            $currentUser->userrole = 4; //Customer Role

            if(!$currentUser || $currentUser->active == 0){
                $this->Authentication_model->logout();
                set_alert('danger', $this->CI->lang->line('admin_auth_inactive_account'));
                redirect(site_url());
            }
        }
        else{
            // Update user last activity
            $this->db->where('userid',get_user_id());
            $this->db->update('tblusers',array('last_activity'=>date('Y-m-d H:i:s')));

            // Deleted or inactive but have session
            $currentUser = $this->User_model->get(get_user_id());
            if(!$currentUser || $currentUser->active == 0){
                $this->Authentication_model->logout();
                set_alert('danger', $this->CI->lang->line('admin_auth_inactive_account'));
                redirect(site_url());
            }
        }

        //$language = load_admin_language();
	$GLOBALS['current_user'] = $currentUser;
    // echo "<pre>";
    // print_r($GLOBALS['current_user']);
    // die();
	//Default Currency
        $curow = $this->Currencies_model->get(false, " isdefault=1 ");
        $GLOBALS['currency_data']['currency_name'] = $curow[0]['name'];
        $GLOBALS['currency_data']['currency_symbol'] = $curow[0]['symbol'];

        //Company detail
        $GLOBALS['company_data']['company_name'] = get_option('company_name');
        $GLOBALS['company_data']['company_address'] = get_option('company_address');
        $GLOBALS['company_data']['company_zipcode'] = get_option('company_zipcode');
        $GLOBALS['company_data']['company_city'] = get_option('company_city');
        $GLOBALS['company_data']['company_business_partner'] = get_option('company_business_partner');
        $GLOBALS['company_data']['company_business_partner_name'] = get_option('company_business_partner_name');
        $GLOBALS['company_data']['company_address2'] = get_option('company_address2');
        $GLOBALS['company_data']['company_address3'] = get_option('company_address3');
        $GLOBALS['company_data']['company_tel'] = get_option('company_tel');
        $GLOBALS['company_data']['company_fax'] = get_option('company_fax');
        $GLOBALS['company_data']['company_website'] = get_option('company_website');
        $GLOBALS['company_data']['company_email'] = get_option('company_email');

        $GLOBALS['company_data']['company_business_partner_telekom'] = 'Telekom Business Partner';
        $GLOBALS['company_data']['company_business_partner_name_telekom'] = 'Zertifizierter Business Service Partner der Telekom GmbH';
        $GLOBALS['company_data']['company_business_partner_o2'] = 'o2 Business Partner';
        $GLOBALS['company_data']['company_business_partner_name_o2'] = 'Zertifizierter o2 Business Service Partner';


        //Role Permission Settings
        $GLOBALS['viewable_permission'] = array('user','ratemobile','ratelandline','optionmobile','optionlandline','discountlevel','hardware','supplier','documentsetting','employeecommission','lead','leadcomment','customer','customercomment','todo','todocomment','quotation','a_leadquotation','quotationcomment','assignment','hardwareinput','hardwareassignment','ticket','ticketcomment','deliverynote','hardwareinvoice','qualitycheck','calendar','monitoring','monitoringcomment','document','a_pin_puk','a_invoice','a_hardwareinventory','a_moreoptionmobile','infodocument','history','a_termination','a_hardwarebudget');
        $GLOBALS['viewable_own_permission'] = array('lead','employeecommission','ticket','quotation','a_leadquotation','assignment','hardwareassignment','hardwareinvoice','monitoring');//For POS and Customer
        $GLOBALS['creatable_permission'] = array('user','ratemobile','ratelandline','optionmobile','optionlandline','discountlevel','hardware','supplier','documentsetting','lead','leadcomment','leadtocustomer','customer','customercomment','todo','todocomment','quotation','a_leadquotation','quotationcomment','quotationtoassignment','assignment','hardwareinput','ticket','ticketcomment','calendar','monitoringcomment','document','a_pin_puk','a_subscriptionlock','a_subscriptionlock2','a_optionbook','a_hardwareorder','a_ultracardorder','a_cardpause','a_cardbreak','a_invoice','a_contractorder','a_moreoptionmobile','a_repairorder','a_rebuyorder','a_bookinsurance','a_hardwareuploaddocument','infodocument','a_termination','a_hardwarebudget');
        $GLOBALS['editable_permission'] = array('user','ratemobile','ratelandline','optionmobile','optionlandline','discountlevel','hardware','supplier','documentsetting','lead','leadcomment','customer','customercomment','todo','todocomment','quotation','a_leadquotation','quotationcomment','assignment','hardwareinput','hardwareassignment','ticket','ticketcomment','qualitycheck','calendar','monitoring','monitoringcomment','document','profile','customerprofile','a_invoice','infodocument','a_termination','a_hardwarebudget');
        $GLOBALS['deletable_permission'] = array('user','ratemobile','ratelandline','optionmobile','optionlandline','discountlevel','hardware','supplier','documentsetting','lead','leadcomment','customer','customercomment','todo','todocomment','quotation','a_leadquotation','quotationcomment','assignment','hardwareinput','hardwareassignment','ticket','ticketcomment','deliverynote','hardwareinvoice','qualitycheck','calendar','monitoring','monitoringcomment','document','a_invoice','infodocument','a_termination','a_hardwarebudget');
        $GLOBALS['importable_permission'] = array('ratemobile', 'ratelandline', 'optionmobile', 'optionlandline', 'discountlevel', 'hardware', 'lead','termination');


        //Function Array for Role Permission
        $role_permission = array('view'=>has_permission('role','view'),
            'view_own'=>has_permission('role','view_own'),
            'create'=>has_permission('role','create'),
            'edit'=>has_permission('role','edit'),
            'delete'=>has_permission('role','delete'),
            'import'=>has_permission('role','import'),
        );
        $GLOBALS['role_permission'] = $role_permission;

        //Function Array for User Permission
        $user_permission = array('view'=>has_permission('user','view'),
            'view_own'=>has_permission('user','view_own'),
            'create'=>has_permission('user','create'),
            'edit'=>has_permission('user','edit'),
            'delete'=>has_permission('user','delete'),
            'import'=>has_permission('user','import'),
        );
        $GLOBALS['user_permission'] = $user_permission;

        //Function Array for Mobile Rate Permission
        $ratemobile_permission = array('view'=>has_permission('ratemobile','view'),
            'view_own'=>has_permission('ratemobile','view_own'),
            'create'=>has_permission('ratemobile','create'),
            'edit'=>has_permission('ratemobile','edit'),
            'delete'=>has_permission('ratemobile','delete'),
            'import'=>has_permission('ratemobile','import'),
        );
        $GLOBALS['ratemobile_permission'] = $ratemobile_permission;

        //Function Array for Landline Rate Permission
        $ratelandline_permission = array('view'=>has_permission('ratelandline','view'),
            'view_own'=>has_permission('ratelandline','view_own'),
            'create'=>has_permission('ratelandline','create'),
            'edit'=>has_permission('ratelandline','edit'),
            'delete'=>has_permission('ratelandline','delete'),
            'import'=>has_permission('ratelandline','import'),
        );
        $GLOBALS['ratelandline_permission'] = $ratelandline_permission;

        //Function Array for Mobile Option Permission
        $optionmobile_permission = array('view'=>has_permission('optionmobile','view'),
            'view_own'=>has_permission('optionmobile','view_own'),
            'create'=>has_permission('optionmobile','create'),
            'edit'=>has_permission('optionmobile','edit'),
            'delete'=>has_permission('optionmobile','delete'),
            'import'=>has_permission('optionmobile','import'),
        );
        $GLOBALS['optionmobile_permission'] = $optionmobile_permission;

        //Function Array for Landline Option Permission
        $optionlandline_permission = array('view'=>has_permission('optionlandline','view'),
            'view_own'=>has_permission('optionlandline','view_own'),
            'create'=>has_permission('optionlandline','create'),
            'edit'=>has_permission('optionlandline','edit'),
            'delete'=>has_permission('optionlandline','delete'),
            'import'=>has_permission('optionlandline','import'),
        );
        $GLOBALS['optionlandline_permission'] = $optionlandline_permission;

        //Function Array for Discount Level Permission
        $discountlevel_permission = array('view'=>has_permission('discountlevel','view'),
            'view_own'=>has_permission('discountlevel','view_own'),
            'create'=>has_permission('discountlevel','create'),
            'edit'=>has_permission('discountlevel','edit'),
            'delete'=>has_permission('discountlevel','delete'),
            'import'=>has_permission('discountlevel','import'),
        );
        $GLOBALS['discountlevel_permission'] = $discountlevel_permission;

        //Function Array for Hardware Permission
        $hardware_permission = array('view'=>has_permission('hardware','view'),
            'view_own'=>has_permission('hardware','view_own'),
            'create'=>has_permission('hardware','create'),
            'edit'=>has_permission('hardware','edit'),
            'delete'=>has_permission('hardware','delete'),
            'import'=>has_permission('hardware','import'),
        );
        $GLOBALS['hardware_permission'] = $hardware_permission;

        //Function Array for Supplier Permission
        $supplier_permission = array('view'=>has_permission('supplier','view'),
            'view_own'=>has_permission('supplier','view_own'),
            'create'=>has_permission('supplier','create'),
            'edit'=>has_permission('supplier','edit'),
            'delete'=>has_permission('supplier','delete'),
            'import'=>has_permission('supplier','import'),
        );
        $GLOBALS['supplier_permission'] = $supplier_permission;

        //Function Array for Supplier Permission
        $documentsetting_permission = array('view'=>has_permission('documentsetting','view'),
            'view_own'=>has_permission('documentsetting','view_own'),
            'create'=>has_permission('documentsetting','create'),
            'edit'=>has_permission('documentsetting','edit'),
            'delete'=>has_permission('documentsetting','delete'),
            'import'=>has_permission('documentsetting','import'),
        );
        $GLOBALS['documentsetting_permission'] = $documentsetting_permission;

        //Function Array for Employee Commision
        $employeecommission_permission = array('view'=>has_permission('employeecommission','view'),
            'view_own'=>has_permission('employeecommission','view_own'),
            'create'=>has_permission('employeecommission','create'),
            'edit'=>has_permission('employeecommission','edit'),
            'delete'=>has_permission('employeecommission','delete'),
            'import'=>has_permission('employeecommission','import'),
        );
        $GLOBALS['employeecommission_permission'] = $employeecommission_permission;

        //Function Array for Lead
        $lead_permission = array('view'=>has_permission('lead','view'),
            'view_own'=>has_permission('lead','view_own'),
            'create'=>has_permission('lead','create'),
            'edit'=>has_permission('lead','edit'),
            'delete'=>has_permission('lead','delete'),
            'import'=>has_permission('lead','import'),
        );
        $GLOBALS['lead_permission'] = $lead_permission;

	//Function Array for Lead Comment
        $leadcomment_permission = array('view'=>has_permission('leadcomment','view'),
            'view_own'=>has_permission('leadcomment','view_own'),
            'create'=>has_permission('leadcomment','create'),
            'edit'=>has_permission('leadcomment','edit'),
            'delete'=>has_permission('leadcomment','delete'),
            'import'=>has_permission('leadcomment','import'),
        );
        $GLOBALS['leadcomment_permission'] = $leadcomment_permission;

        //Function Array for Lead To Customer
        $leadtocustomer_permission = array('view'=>has_permission('leadtocustomer','view'),
            'view_own'=>has_permission('leadtocustomer','view_own'),
            'create'=>has_permission('leadtocustomer','create'),
            'edit'=>has_permission('leadtocustomer','edit'),
            'delete'=>has_permission('leadtocustomer','delete'),
            'import'=>has_permission('leadtocustomer','import'),
        );
        $GLOBALS['leadtocustomer_permission'] = $leadtocustomer_permission;

        //Function Array for Customer
        $customer_permission = array('view'=>has_permission('customer','view'),
            'view_own'=>has_permission('customer','view_own'),
            'create'=>has_permission('customer','create'),
            'edit'=>has_permission('customer','edit'),
            'delete'=>has_permission('customer','delete'),
            'import'=>has_permission('customer','import'),
        );
        $GLOBALS['customer_permission'] = $customer_permission;

        //Function Array for Customer Comment
        $customercomment_permission = array('view'=>has_permission('customercomment','view'),
            'view_own'=>has_permission('customercomment','view_own'),
            'create'=>has_permission('customercomment','create'),
            'edit'=>has_permission('customercomment','edit'),
            'delete'=>has_permission('customercomment','delete'),
            'import'=>has_permission('customercomment','import'),
        );
        $GLOBALS['customercomment_permission'] = $customercomment_permission;

        //Function Array for Todo
        $todo_permission = array('view'=>has_permission('todo','view'),
            'view_own'=>has_permission('todo','view_own'),
            'create'=>has_permission('todo','create'),
            'edit'=>has_permission('todo','edit'),
            'delete'=>has_permission('todo','delete'),
            'import'=>has_permission('todo','import'),
        );
        $GLOBALS['todo_permission'] = $todo_permission;

        //Function Array for Todo Comment
        $todocomment_permission = array('view'=>has_permission('todocomment','view'),
            'view_own'=>has_permission('todocomment','view_own'),
            'create'=>has_permission('todocomment','create'),
            'edit'=>has_permission('todocomment','edit'),
            'delete'=>has_permission('todocomment','delete'),
            'import'=>has_permission('todocomment','import'),
        );
        $GLOBALS['todocomment_permission'] = $todocomment_permission;

        //Function Array for Quotation
        $quotation_permission = array('view'=>has_permission('quotation','view'),
            'view_own'=>has_permission('quotation','view_own'),
            'create'=>has_permission('quotation','create'),
            'edit'=>has_permission('quotation','edit'),
            'delete'=>has_permission('quotation','delete'),
            'import'=>has_permission('quotation','import'),
        );
        $GLOBALS['quotation_permission'] = $quotation_permission;

        //Function Array for Quotation
        $leadquotation_permission = array('view'=>has_permission('a_leadquotation','view'),
            'view_own'=>has_permission('a_leadquotation','view_own'),
            'create'=>has_permission('a_leadquotation','create'),
            'edit'=>has_permission('a_leadquotation','edit'),
            'delete'=>has_permission('a_leadquotation','delete'),
            'import'=>has_permission('a_leadquotation','import'),
        );
        $GLOBALS['leadquotation_permission'] = $leadquotation_permission;

        //Function Array for Lead To Customer
        $quotationtoassignment_permission = array('view'=>has_permission('quotationtoassignment','view'),
            'view_own'=>has_permission('quotationtoassignment','view_own'),
            'create'=>has_permission('quotationtoassignment','create'),
            'edit'=>has_permission('quotationtoassignment','edit'),
            'delete'=>has_permission('quotationtoassignment','delete'),
            'import'=>has_permission('quotationtoassignment','import'),
        );
        $GLOBALS['quotationtoassignment_permission'] = $quotationtoassignment_permission;

        //Function Array for Quotation Comment
        $quotationcomment_permission = array('view'=>has_permission('quotationcomment','view'),
            'view_own'=>has_permission('quotationcomment','view_own'),
            'create'=>has_permission('quotationcomment','create'),
            'edit'=>has_permission('quotationcomment','edit'),
            'delete'=>has_permission('quotationcomment','delete'),
            'import'=>has_permission('quotationcomment','import'),
        );
        $GLOBALS['quotationcomment_permission'] = $quotationcomment_permission;

        //Function Array for Assignment
        $assignment_permission = array('view'=>has_permission('assignment','view'),
            'view_own'=>has_permission('assignment','view_own'),
            'create'=>has_permission('assignment','create'),
            'edit'=>has_permission('assignment','edit'),
            'delete'=>has_permission('assignment','delete'),
            'import'=>has_permission('assignment','import'),
        );
        $GLOBALS['assignment_permission'] = $assignment_permission;

        //Function Array for Hardwareinput
        $hardwareinput_permission = array('view'=>has_permission('hardwareinput','view'),
            'view_own'=>has_permission('hardwareinput','view_own'),
            'create'=>has_permission('hardwareinput','create'),
            'edit'=>has_permission('hardwareinput','edit'),
            'delete'=>has_permission('hardwareinput','delete'),
            'import'=>has_permission('hardwareinput','import'),
        );
        $GLOBALS['hardwareinput_permission'] = $hardwareinput_permission;

        //Function Array for Hardwareassignment
        $hardwareassignment_permission = array('view'=>has_permission('hardwareassignment','view'),
            'view_own'=>has_permission('hardwareassignment','view_own'),
            'create'=>has_permission('hardwareassignment','create'),
            'edit'=>has_permission('hardwareassignment','edit'),
            'delete'=>has_permission('hardwareassignment','delete'),
            'import'=>has_permission('hardwareassignment','import'),
        );
        $GLOBALS['hardwareassignment_permission'] = $hardwareassignment_permission;

        //Function Array for Delivery Note
        $deliverynote_permission = array('view'=>has_permission('deliverynote','view'),
            'view_own'=>has_permission('deliverynote','view_own'),
            'create'=>has_permission('deliverynote','create'),
            'edit'=>has_permission('deliverynote','edit'),
            'delete'=>has_permission('deliverynote','delete'),
            'import'=>has_permission('deliverynote','import'),
        );
        $GLOBALS['deliverynote_permission'] = $deliverynote_permission;

        //Function Array for Hardware Invoice
        $hardwareinvoice_permission = array('view'=>has_permission('hardwareinvoice','view'),
            'view_own'=>has_permission('hardwareinvoice','view_own'),
            'create'=>has_permission('hardwareinvoice','create'),
            'edit'=>has_permission('hardwareinvoice','edit'),
            'delete'=>has_permission('hardwareinvoice','delete'),
            'import'=>has_permission('hardwareinvoice','import'),
        );
        $GLOBALS['hardwareinvoice_permission'] = $hardwareinvoice_permission;

        //Function Array for Ticket
        $ticket_permission = array('view'=>has_permission('ticket','view'),
            'view_own'=>has_permission('ticket','view_own'),
            'create'=>has_permission('ticket','create'),
            'edit'=>has_permission('ticket','edit'),
            'delete'=>has_permission('ticket','delete'),
            'import'=>has_permission('ticket','import'),
        );
        $GLOBALS['ticket_permission'] = $ticket_permission;

        //Function Array for Ticket
        $ticketcomment_permission = array('view'=>has_permission('ticketcomment','view'),
            'view_own'=>has_permission('ticketcomment','view_own'),
            'create'=>has_permission('ticketcomment','create'),
            'edit'=>has_permission('ticketcomment','edit'),
            'delete'=>has_permission('ticketcomment','delete'),
            'import'=>has_permission('ticketcomment','import'),
        );
        $GLOBALS['ticketcomment_permission'] = $ticketcomment_permission;

        //Function Array for Qualitycheck
        $qualitycheck_permission = array('view'=>has_permission('qualitycheck','view'),
            'view_own'=>has_permission('qualitycheck','view_own'),
            'create'=>has_permission('qualitycheck','create'),
            'edit'=>has_permission('qualitycheck','edit'),
            'delete'=>has_permission('qualitycheck','delete'),
            'import'=>has_permission('qualitycheck','import'),
        );
        $GLOBALS['qualitycheck_permission'] = $qualitycheck_permission;

        //Function Array for Calendar
        $calendar_permission = array('view'=>has_permission('calendar','view'),
            'view_own'=>has_permission('calendar','view_own'),
            'create'=>has_permission('calendar','create'),
            'edit'=>has_permission('calendar','edit'),
            'delete'=>has_permission('calendar','delete'),
            'import'=>has_permission('calendar','import'),
        );
        $GLOBALS['calendar_permission'] = $calendar_permission;

        //Function Array for Monitoring
        $monitoring_permission = array('view'=>has_permission('monitoring','view'),
            'view_own'=>has_permission('monitoring','view_own'),
            'create'=>has_permission('monitoring','create'),
            'edit'=>has_permission('monitoring','edit'),
            'delete'=>has_permission('monitoring','delete'),
            'import'=>has_permission('monitoring','import'),
        );
        $GLOBALS['monitoring_permission'] = $monitoring_permission;

        //Function Array for Monitoring
        $monitoringcomment_permission = array('view'=>has_permission('monitoringcomment','view'),
            'view_own'=>has_permission('monitoringcomment','view_own'),
            'create'=>has_permission('monitoringcomment','create'),
            'edit'=>has_permission('monitoringcomment','edit'),
            'delete'=>has_permission('monitoringcomment','delete'),
            'import'=>has_permission('monitoringcomment','import'),
        );
        $GLOBALS['monitoringcomment_permission'] = $monitoringcomment_permission;

        //Function Array for Document
        $document_permission = array('view'=>has_permission('document','view'),
            'view_own'=>has_permission('document','view_own'),
            'create'=>has_permission('document','create'),
            'edit'=>has_permission('document','edit'),
            'delete'=>has_permission('document','delete'),
            'import'=>has_permission('document','import'),
        );
        $GLOBALS['document_permission'] = $document_permission;

        //Function Array for Profile
        $profile_permission = array('view'=>has_permission('profile','view'),
            'view_own'=>has_permission('profile','view_own'),
            'create'=>has_permission('profile','create'),
            'edit'=>has_permission('profile','edit'),
            'delete'=>has_permission('profile','delete'),
            'import'=>has_permission('profile','import'),
        );
        $GLOBALS['profile_permission'] = $profile_permission;

        //Function Array for Customer Profile
        /*$customerprofile_permission = array('view'=>has_permission('customerprofile','view'),
            'view_own'=>has_permission('customerprofile','view_own'),
            'create'=>has_permission('customerprofile','create'),
            'edit'=>has_permission('customerprofile','edit'),
            'delete'=>has_permission('customerprofile','delete'),
            'import'=>has_permission('customerprofile','import'),
        );
        $GLOBALS['customerprofile_permission'] = $customerprofile_permission;*/


        /***********************/
        /* ASSINMENT BUTTONS */
        /***********************/

        $a_subscriptionlock_permission = array('view'=>has_permission('a_subscriptionlock','view'),
            'view_own'=>has_permission('a_subscriptionlock','view_own'),
            'create'=>has_permission('a_subscriptionlock','create'),
            'edit'=>has_permission('a_subscriptionlock','edit'),
            'delete'=>has_permission('a_subscriptionlock','delete'),
            'import'=>has_permission('a_subscriptionlock','import'),
        );
        $GLOBALS['a_subscriptionlock_permission'] = $a_subscriptionlock_permission;

        $a_subscriptionlock2_permission = array('view'=>has_permission('a_subscriptionlock2','view'),
            'view_own'=>has_permission('a_subscriptionlock2','view_own'),
            'create'=>has_permission('a_subscriptionlock2','create'),
            'edit'=>has_permission('a_subscriptionlock2','edit'),
            'delete'=>has_permission('a_subscriptionlock2','delete'),
            'import'=>has_permission('a_subscriptionlock2','import'),
        );
        $GLOBALS['a_subscriptionlock2_permission'] = $a_subscriptionlock2_permission;

        $a_optionbook_permission = array('view'=>has_permission('a_optionbook','view'),
            'view_own'=>has_permission('a_optionbook','view_own'),
            'create'=>has_permission('a_optionbook','create'),
            'edit'=>has_permission('a_optionbook','edit'),
            'delete'=>has_permission('a_optionbook','delete'),
            'import'=>has_permission('a_optionbook','import'),
        );
        $GLOBALS['a_optionbook_permission'] = $a_optionbook_permission;

        $a_hardwareorder_permission = array('view'=>has_permission('a_hardwareorder','view'),
            'view_own'=>has_permission('a_hardwareorder','view_own'),
            'create'=>has_permission('a_hardwareorder','create'),
            'edit'=>has_permission('a_hardwareorder','edit'),
            'delete'=>has_permission('a_hardwareorder','delete'),
            'import'=>has_permission('a_hardwareorder','import'),
        );
        $GLOBALS['a_hardwareorder_permission'] = $a_hardwareorder_permission;

        $a_ultracardorder_permission = array('view'=>has_permission('a_ultracardorder','view'),
            'view_own'=>has_permission('a_ultracardorder','view_own'),
            'create'=>has_permission('a_ultracardorder','create'),
            'edit'=>has_permission('a_ultracardorder','edit'),
            'delete'=>has_permission('a_ultracardorder','delete'),
            'import'=>has_permission('a_ultracardorder','import'),
        );
        $GLOBALS['a_ultracardorder_permission'] = $a_ultracardorder_permission;

        $a_pin_puk_permission = array('view'=>has_permission('a_pin_puk','view'),
            'view_own'=>has_permission('a_pin_puk','view_own'),
            'create'=>has_permission('a_pin_puk','create'),
            'edit'=>has_permission('a_pin_puk','edit'),
            'delete'=>has_permission('a_pin_puk','delete'),
            'import'=>has_permission('a_pin_puk','import'),
        );
        //print_r($a_pin_puk_permission);exit;

        $GLOBALS['a_pin_puk_permission'] = $a_pin_puk_permission;

        $a_cardpause_permission = array('view'=>has_permission('a_cardpause','view'),
            'view_own'=>has_permission('a_cardpause','view_own'),
            'create'=>has_permission('a_cardpause','create'),
            'edit'=>has_permission('a_cardpause','edit'),
            'delete'=>has_permission('a_cardpause','delete'),
            'import'=>has_permission('a_cardpause','import'),
        );
        $GLOBALS['a_cardpause_permission'] = $a_cardpause_permission;

        $a_cardbreak_permission = array('view'=>has_permission('a_cardbreak','view'),
            'view_own'=>has_permission('a_cardbreak','view_own'),
            'create'=>has_permission('a_cardbreak','create'),
            'edit'=>has_permission('a_cardbreak','edit'),
            'delete'=>has_permission('a_cardbreak','delete'),
            'import'=>has_permission('a_cardbreak','import'),
        );
        $GLOBALS['a_cardbreak_permission'] = $a_cardbreak_permission;

        $a_invoice_permission = array('view'=>has_permission('a_invoice','view'),
            'view_own'=>has_permission('a_invoice','view_own'),
            'create'=>has_permission('a_invoice','create'),
            'edit'=>has_permission('a_invoice','edit'),
            'delete'=>has_permission('a_invoice','delete'),
            'import'=>has_permission('a_invoice','import'),
        );
        $GLOBALS['a_invoice_permission'] = $a_invoice_permission;

        $a_hardwareinventory_permission = array('view'=>has_permission('a_hardwareinventory','view'),
            'view_own'=>has_permission('a_hardwareinventory','view_own'),
            'create'=>has_permission('a_hardwareinventory','create'),
            'edit'=>has_permission('a_hardwareinventory','edit'),
            'delete'=>has_permission('a_hardwareinventory','delete'),
            'import'=>has_permission('a_hardwareinventory','import'),
        );
        $GLOBALS['a_hardwareinventory_permission'] = $a_hardwareinventory_permission;

        $a_contractorder_permission = array('view'=>has_permission('a_contractorder','view'),
            'view_own'=>has_permission('a_contractorder','view_own'),
            'create'=>has_permission('a_contractorder','create'),
            'edit'=>has_permission('a_contractorder','edit'),
            'delete'=>has_permission('a_contractorder','delete'),
            'import'=>has_permission('a_contractorder','import'),
        );
        $GLOBALS['a_contractorder_permission'] = $a_contractorder_permission;

        $a_moreoptionmobile_permission = array('view'=>has_permission('a_moreoptionmobile','view'),
            'view_own'=>has_permission('a_moreoptionmobile','view_own'),
            'create'=>has_permission('a_moreoptionmobile','create'),
            'edit'=>has_permission('a_moreoptionmobile','edit'),
            'delete'=>has_permission('a_moreoptionmobile','delete'),
            'import'=>has_permission('a_moreoptionmobile','import'),
        );
        $GLOBALS['a_moreoptionmobile_permission'] = $a_moreoptionmobile_permission;

        $a_repairorder_permission = array('view'=>has_permission('a_repairorder','view'),
            'view_own'=>has_permission('a_repairorder','view_own'),
            'create'=>has_permission('a_repairorder','create'),
            'edit'=>has_permission('a_repairorder','edit'),
            'delete'=>has_permission('a_repairorder','delete'),
            'import'=>has_permission('a_repairorder','import'),
        );
        $GLOBALS['a_repairorder_permission'] = $a_repairorder_permission;

        $a_rebuyorder_permission = array('view'=>has_permission('a_rebuyorder','view'),
            'view_own'=>has_permission('a_rebuyorder','view_own'),
            'create'=>has_permission('a_rebuyorder','create'),
            'edit'=>has_permission('a_rebuyorder','edit'),
            'delete'=>has_permission('a_rebuyorder','delete'),
            'import'=>has_permission('a_rebuyorder','import'),
        );
        $GLOBALS['a_rebuyorder_permission'] = $a_rebuyorder_permission;

        $a_bookinsurance_permission = array('view'=>has_permission('a_bookinsurance','view'),
            'view_own'=>has_permission('a_bookinsurance','view_own'),
            'create'=>has_permission('a_bookinsurance','create'),
            'edit'=>has_permission('a_bookinsurance','edit'),
            'delete'=>has_permission('a_bookinsurance','delete'),
            'import'=>has_permission('a_bookinsurance','import'),
        );
        $GLOBALS['a_bookinsurance_permission'] = $a_bookinsurance_permission;

        $a_hardwareuploaddocument_permission = array('view'=>has_permission('a_hardwareuploaddocument','view'),
            'view_own'=>has_permission('a_hardwareuploaddocument','view_own'),
            'create'=>has_permission('a_hardwareuploaddocument','create'),
            'edit'=>has_permission('a_hardwareuploaddocument','edit'),
            'delete'=>has_permission('a_hardwareuploaddocument','delete'),
            'import'=>has_permission('a_hardwareuploaddocument','import'),
        );
        $GLOBALS['a_hardwareuploaddocument_permission'] = $a_hardwareuploaddocument_permission;

        /***********************/
        /* END ASSINMENT BUTTONS */
        /***********************/

        //Function Array for Info Document
        $infodocument_permission = array('view'=>has_permission('infodocument','view'),
            'view_own'=>has_permission('infodocument','view_own'),
            'create'=>has_permission('infodocument','create'),
            'edit'=>has_permission('infodocument','edit'),
            'delete'=>has_permission('infodocument','delete'),
            'import'=>has_permission('infodocument','import'),
        );
        $GLOBALS['infodocument_permission'] = $infodocument_permission;

        //Function Array for History
        $history_permission = array('view'=>has_permission('history','view'),
            'view_own'=>has_permission('history','view_own'),
            'create'=>has_permission('history','create'),
            'edit'=>has_permission('history','edit'),
            'delete'=>has_permission('history','delete'),
            'import'=>has_permission('history','import'),
        );
        $GLOBALS['history_permission'] = $history_permission;


        //Function Array for Hardwarebudget
        $hardwarebudget_permission = array('view'=>has_permission('a_hardwarebudget','view'),
            'view_own'=>has_permission('a_hardwarebudget','view_own'),
            'create'=>has_permission('a_hardwarebudget','create'),
            'edit'=>has_permission('a_hardwarebudget','edit'),
            'delete'=>has_permission('a_hardwarebudget','delete'),
            'import'=>has_permission('a_hardwarebudget','import'),
        );
        $GLOBALS['hardwarebudget_permission'] = $hardwarebudget_permission;

        //Function Array for Termination
        $termination_permission = array('view'=>has_permission('a_termination','view'),
            'view_own'=>has_permission('a_termination','view_own'),
            'create'=>has_permission('a_termination','create'),
            'edit'=>has_permission('a_termination','edit'),
            'delete'=>has_permission('a_termination','delete'),
            'import'=>has_permission('a_termination','import'),
        );
        $GLOBALS['termination_permission'] = $termination_permission;
        // echo "<pre>";
        // print_r($a_termination_permission);
        // die();
        //Auto loaded vars for global
        $auto_loaded_vars = array(
            'current_user' => $currentUser,
            'role_permission' => $role_permission,
            'user_permission' => $user_permission,
            'ratemobile_permission' => $ratemobile_permission,
            'ratelandline_permission' => $ratelandline_permission,
            'optionmobile_permission' => $optionmobile_permission,
            'optionlandline_permission' => $optionlandline_permission,
            'discountlevel_permission' => $discountlevel_permission,
            'hardware_permission' => $hardware_permission,
            'supplier_permission' => $supplier_permission,
            'documentsetting_permission' => $documentsetting_permission,
            'employeecommission_permission' => $employeecommission_permission,
            'lead_permission' => $lead_permission,
            'leadcomment_permission' => $leadcomment_permission,
            'leadtocustomer_permission' => $leadtocustomer_permission,
            'customer_permission' => $customer_permission,
            'customercomment_permission' => $customercomment_permission,
            'todo_permission' => $todo_permission,
            'todocomment_permission' => $todocomment_permission,
            'quotation_permission' => $quotation_permission,
            'leadquotation_permission' => $leadquotation_permission,
            'quotationtoassignment_permission' => $quotationtoassignment_permission,
            'quotationcomment_permission' => $quotationcomment_permission,
            'assignment_permission' => $assignment_permission,
            'hardwareinput_permission' => $hardwareinput_permission,
            'hardwareassignment_permission' => $hardwareassignment_permission,
            'ticket_permission' => $ticket_permission,
            'ticketcomment_permission' => $ticketcomment_permission,
            'deliverynote_permission' => $deliverynote_permission,
            'hardwareinvoice_permission' => $hardwareinvoice_permission,
            'qualitycheck_permission' => $qualitycheck_permission,
            'calendar_permission' => $calendar_permission,
            'monitoring_permission' => $monitoring_permission,
            'monitoringcomment_permission' => $monitoringcomment_permission,
            'document_permission' => $document_permission,
            'profile_permission' => $profile_permission,
            'infodocument_permission' => $infodocument_permission,
            'history_permission' => $history_permission,
            'hardwarebudget_permission' => $hardwarebudget_permission,
            'termination_permission' => $termination_permission
        );

        $auto_loaded_vars = do_action('before_set_auto_loaded_vars_admin_area', $auto_loaded_vars);
        $this->load->vars($auto_loaded_vars);
    }

      // common query

    public function insert($table,$data= array())
    {
        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    public function select_record($table,$field='',$param = array(),$where = array())
    {

        if($field !='') {
            $this->db->select($field);
        }

        $this->db->from($table);

        if(array_key_exists('where', $param) && count($param['where']) > 0){
            foreach ($param['where'] as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        // if (!empty($where) && count($where) > 0) {
        //     $this->db->where($where);
        // }

        if (array_key_exists('like', $param)) {
            $this->db->like($param['like'][0], $param['like'][1]);
        }

        if (array_key_exists('or_like', $param)) {
            $this->db->or_like($param['or_like'][0], $param['or_like'][1]);
        }

        if (array_key_exists('order_by', $param)) {
            $this->db->order_by($param['order_by'][0], $param['order_by'][1]);
        }

        if(array_key_exists('join', $param) && count($param['join']) > 0){
            foreach ($param['join'] as $key=>$value){
                $this->db->join($key,$value, 'left');
            }
        }

        if (array_key_exists('limit', $param)) {
            $this->db->limit($param['limit'][0],$param['limit'][1]);
        }

        $query = $this->db->get();

        return $query->result_array();
    }

    public function countAllRecord($table = '',$where = array())
    {
        $this->db->select('*');
        $this->db->from($table);

        if (!empty($where) && count($where) > 0) {
           $this->db->where($where);
        }

        $query = $this->db->get();

        return $query->num_rows();

    }
    public function get_single_record($table,$where = array())
    {
        $this->db->select('*');
        $this->db->from($table);

        if (!empty($where) && count($where) > 0) {
            $this->db->where($where);
        }

        $query = $this->db->get();

        return $query->row_array();
    }

    public function delete_record($table,$where = array())
    {
        if (!empty($where) && count($where) > 0) {
            $this->db->where($where);
        }
        return $this->db->delete($table);
    }

    public function update_record($table,$where = array(),$data = array())
    {
        if (!empty($where) && count($where) > 0) {
            $this->db->where($where);
        }
        return $this->db->update($table, $data);
    }

    public function insert_batch($table,$data = array())
    {
        $this->db->insert_batch($table,$data);
        $limit = $this->db->affected_rows();

        $query = $this->db
                ->select('id')
                ->from($table)
                ->order_by('id','DESC')
                ->group_by('id')
                ->limit($limit)
                ->get();
       $affectedRowId = $query->result_array();

       return array_reverse($affectedRowId);
    }

    public function update_batch($table,$data = array(),$updateKey)
    {
       return  $this->db->update_batch($table, $data, $updateKey);
    }
}
