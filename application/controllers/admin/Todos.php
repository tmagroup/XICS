<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Todos extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Todo_model');
        $this->load->model('User_model');
        $this->load->model('Customer_model');
        $this->load->model('Todostatus_model');
        $this->load->model('Note_model');
    }

    /* List all todos */
    public function index()
    {
        if(!$GLOBALS['todo_permission']['view']){
            access_denied('todo');
        }
        
        //******************** Initialise ********************/
        //Responsibles (Users of Customer)
        $data['filter_responsible'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",                
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible')                
        );
        
        $data['filter_responsible'] = dropdown($data['filter_responsible'],'userid','name');
        
        //Todostatus
        $data['filter_todostatus'] = $this->Todostatus_model->get();
        $data['filter_todostatus'] = dropdown($data['filter_todostatus'],'id','name'); 
        //******************** End Initialise ********************/
        
        $data['title'] = lang('page_todos');
        $this->load->view('admin/todos/manage', $data);
    }
    
    /* List all todos by ajax */
    public function ajax($filter_responsible='',$filter_todostatus='')
    {
        //Filter By responsible, todostatus
        $params = array('filter_responsible'=>$filter_responsible,'filter_todostatus'=>$filter_todostatus);	        
        $this->app->get_table_data('todos',$params);        
    }
	
    /* Add/Edit Todo */
    public function todo($id='')
    {
        if(!$GLOBALS['todo_permission']['create'] && !$GLOBALS['todo_permission']['edit']){
            access_denied('todo');
        }
        
        //******************** Initialise ********************/
        if($id>0){
            //Todo
            $data['todo'] = (array) $this->Todo_model->get($id);            
        }
        //******************** End Initialise ********************/
         
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post(); 
           
            if(isset($data['todo']['todonr'])){
                $response = $this->Todo_model->update($post, $data['todo']['todonr']);  
                if (is_numeric($response) && $response>0) {
                    
                    //Reminder of Comment to Responsible, Teamwork
                    /*$post['reminderway'] = isset($post['reminderway'])?1:0; 
                    if($post['reminderway']==0){
                        $this->Todo_model->sendReminder($response);
                    }*/    
                    
                    //History 
                    $Action_data = array('actionname'=>'todo', 'actionid'=>$response, 'actiontitle'=>'todo_updated');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_todo')));
                    redirect(site_url('admin/todos/detail/' . $data['todo']['todonr']));                    
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Todo_model->add($post);
                if (is_numeric($response) && $response>0) {
                    
                    //Reminder of Comment to Responsible, Teamwork
                    $post['reminderway'] = isset($post['reminderway'])?1:0; 
                    if($post['reminderway']==0){
                        $this->Todo_model->sendReminder($response,'single');
                    }
                    
                    //History 
                    $Action_data = array('actionname'=>'todo', 'actionid'=>$response, 'actiontitle'=>'todo_added');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('added_successfully'),lang('page_todo')));
                    redirect(site_url('admin/todos/detail/' . $response));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            
            //Initialise    
            $todonr = '';
            if(isset($data['todo'])){
                $todonr = $data['todo']['todonr'];
            }
            $data['todo'] = $post;
            $data['todo']['todonr'] = $todonr;
        }
        
        
        //******************** Initialise ********************/
        //Todostatus
        $data['todostatus'] = $this->Todostatus_model->get();
        $data['todostatus'] = dropdown($data['todostatus'],'id','name');      
        
        //Responsibles (Users of Customer)
        /*$data['responsibles'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",                
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible')                
        );
        $data['responsibles'] = dropdown($data['responsibles'],'userid','name');*/
        
        //Customers
        //$data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, CONCAT(tblcustomers.name,' ',tblcustomers.surname) as name");
        $data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, tblcustomers.company");
        $data['customers'] = dropdown($data['customers'],'customernr','company');

        $data['responsibles'] = $this->Customer_model->get('', "tblusers.userid, CONCAT(tblusers.name, ' ', tblusers.surname) AS name",
            array('tblusers' => 'tblusers.userid=tblcustomers.responsible')
        );
        $data['responsibles'] = dropdown($data['responsibles'], 'userid', 'name');

        //Teamwork (Multiple selection)
        $data['teamworks'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole NOT IN(4,6) ");
        //$data['teamworks'] = dropdown($data['teamworks'],'userid','name');  
        //******************** End Initialise ********************/
        
        
        //Page Title
        if(isset($data['todo']['todonr']) && $data['todo']['todonr']>0){
            $data['title'] = lang('page_edit_todo');   
            
            //- On the Dashboard he should only see TODOs which belongs to the User who is logged in. (Salesman and Supporter)
            //I have add a new Todo from admin account. And choose responsive user "Koc Mansur". Now Im logged in as mansurkoc, but I cant see the Todo under Menu Todo and not on the Dashboard.
            if(isset($GLOBALS['current_user']) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==5) && $data['todo']['userid']!=get_user_id() && $data['todo']['responsible']!=get_user_id()){
                redirect(site_url('admin/todos'));
            }
        }
        else{
            $data['title'] = lang('page_create_todo');
        }            
        
       
        $this->load->view('admin/todos/todo', $data);
    }
    
    /* Detail Todo */
    public function detail($id='')
    {
		
        if(!$GLOBALS['todo_permission']['view']){
            access_denied('todo');
        }
        
        //******************** Initialise ********************/
        if($id>0){
            //Todo
            /*$data['todo'] = (array) $this->Todo_model->get($id,"tbltodos.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                    . " tbltodostatus.name as todostatus, "
                    . " (SELECT GROUP_CONCAT(CONCAT(`name`,' ',surname)) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork ",
                    
                    array('tblusers as responsible'=>'responsible.userid=tbltodos.responsible',
                    'tblcustomers as customer'=>'customer.customernr=tbltodos.customer',
                    'tbltodostatus'=>'tbltodostatus.id=tbltodos.todostatus')                    
            );*/ 
            
            $data['todo'] = (array) $this->Todo_model->get($id,"tbltodos.*, customer.company as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, tbltodos.responsible as responsible_id, "
                    . " tbltodostatus.name as todostatus, "
                    . " CONCAT(createduser.name,' ',createduser.surname) as created_by , "
                    . " (SELECT GROUP_CONCAT(CONCAT(`name`,' ',surname)) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork ",
                    
                    array('tblusers as responsible'=>'responsible.userid=tbltodos.responsible',
                    'tblcustomers as customer'=>'customer.customernr=tbltodos.customer',
                    'tbltodostatus'=>'tbltodostatus.id=tbltodos.todostatus',
                    'tblusers as createduser'=>'createduser.userid=tbltodos.userid')                    
            ); 
        }
        
        if(empty($data['todo']['todonr'])){
            redirect(site_url('admin/todos'));
        }
        
        //- On the Dashboard he should only see TODOs which belongs to the User who is logged in. (Salesman and Supporter)
        //I have add a new Todo from admin account. And choose responsive user "Koc Mansur". Now Im logged in as mansurkoc, but I cant see the Todo under Menu Todo and not on the Dashboard.
        if(isset($GLOBALS['current_user']) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==5) && $data['todo']['userid']!=get_user_id() && $data['todo']['responsible_id']!=get_user_id()){
            redirect(site_url('admin/todos'));
        }
        //******************** End Initialise ********************/
         
        
        //******************** Initialise ********************/
        //Comments
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$data['todo']['todonr']."' AND tblnotes.rel_type='todo' ","","tblnotes.id desc");       
        //******************** End Initialise ********************/
        
        
        //Page Title
        $data['title'] = lang('page_detail_todo');
        $this->load->view('admin/todos/detail', $data);
    }
    
    /* Delete todo */
    public function delete()
    {
        if(!$GLOBALS['todo_permission']['delete'] || !$this->input->post('id')){
            access_denied('todo');
        }
        
        $response = $this->Todo_model->delete($this->input->post('id'));
        if ($response==1) {
            
            //History 
            $Action_data = array('actionname'=>'todo', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'todo_deleted');
            do_action_history($Action_data);
            
            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_todo')));
        }else{
            set_alert('danger', $response);
        }            
        redirect(site_url('admin/todos/'));
    }
 
    /* Add a Comment by Ajax */
    public function addComment(){
        if ($this->input->post()) {            
            $post = $this->input->post();              
            $response = $this->Note_model->add($post);            
            if (is_numeric($response) && $response>0) {
                
                //History 
                $Action_data = array('actionname'=>'todo', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'todo_comment_added');
                do_action_history($Action_data);

                //Reminder of Comment to Responsible, Teamwork
                $this->Todo_model->sendReminder($response,'comment');
                
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_todocomment'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            } 
        }
    }
    
    /* Update a Comment by Ajax */
    public function editComment($id){
        if ($this->input->post()) {            
            $post = $this->input->post();  
            $response = $this->Note_model->update($post, $id);            
            if (is_numeric($response) && $response>0) {
                
                //History 
                $Action_data = array('actionname'=>'todo', 'actionid'=>$post['rel_id'], 'actionsubid'=>$id, 'actiontitle'=>'todo_comment_updated');
                do_action_history($Action_data);
                
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_todocomment'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            } 
        }
        exit;
    }
    
    /* Delete a Comment by Ajax */
    public function deleteComment(){
        if($this->input->post('id')){
            $response = $this->Note_model->delete($this->input->post('id'));
            if ($response==1) {
                
                //History 
                $Action_data = array('actionname'=>'todo', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$this->input->post('id'), 'actiontitle'=>'todo_comment_deleted');
                do_action_history($Action_data);
                
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_todocomment'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }    
        exit;
    }
    
    /* Get Comments by Ajax */
    public function getComments($id){        
        //******************** Initialise ********************/
        //Comments
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$id."' AND tblnotes.rel_type='todo' ","","tblnotes.id desc");       
        //******************** End Initialise ********************/
        
        if(count($data['comments']) > 0) {
            $this->load->view('admin/todos/todos_comments_template', $data);
        }            
    }  
    
    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function generatereminder($id='')
    {
        //Reminder to Responsible, Teamwork
        $this->Todo_model->sendReminder($id);
    }
    
    //Responsibles (Users of Customer) by Ajax
    public function getResponsibleOfCustomer($custid){        
        $data['responsibles'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",                
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible'),
            "tblcustomers.customernr='".$custid."'"    
        );
        $data_array = dropdown($data['responsibles'],'userid','name');        
        echo json_encode($data_array);
        exit;
    }
    
    //Generate Todolist if Customer's DateLastContact is older than 3 Months
    public function generatetodo(){
        $dataCustomers = $this->Customer_model->get('','',array()," is_new_contact=0 AND (datediff(NOW(), lastcontact))>90 ");
        if(isset($dataCustomers) && count($dataCustomers)>0){
            foreach($dataCustomers as $dataCustomer){
                $post = array(
                    'todotitle' => 'Kontakt zum Kunden',
                    'company' => $dataCustomer['company'],
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
}