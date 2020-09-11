<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Employeecommission_model extends CI_Model
{
    var $table = 'tblemployeecommissions';
    var $aid = 'productpositionid';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Commissionslip_model');
        $this->load->model('Pdf_model');
        $this->load->model('Email_model');
    }

    /**
     * Check if Employee commission
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
        
        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new ecommission
     * @param array $data ecommission $_POST data
     */
    public function add($data)
    {  
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        
        //Log Activity
        if($data['userid']>0){
            logActivity('New Employee Commission Added [ProductPositionID: ' . $data['productpositionid'] . ', UserID: '.$data['userid'].', Commission:' . $data['ecommision'] . ']');
        }

        //Log Activity
        if($data['posid']>0){
            logActivity('New Employee Commission Added [ProductPositionID: ' . $data['productpositionid'] . ', PosID: '.$data['posid'].', Commission:' . $data['ecommision'] . ']');
        }
    }
    
    /**
     * Update quotation
     * @param  array $data quotation
     * @param  mixed $id   quotation id
     * @return boolean
     */
    public function update($data, $where='')
    {    
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        
        //Where 
        if($where!=""){           
            $this->db->where($where);           
        }
        
        $this->db->update($this->table, $data);
        
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            if($data['userid']>0){
                logActivity('Employee Commission Updated [ProductPositionID: ' . $data['productpositionid'] . ', UserID: '.$data['userid'].', Commission:' . $data['ecommision'] . ']');
            }
            
            //Log Activity
            if($data['posid']>0){
                logActivity('Employee Commission Updated [ProductPositionID: ' . $data['productpositionid'] . ', PosID: '.$data['posid'].', Commission:' . $data['ecommision'] . ']');
            }
        } 
    }  
    
    /* Calculate Employee(Salesman and POS) Comissions
     */
    public function create_employeecommissionslip($month_year){
        
        //Monthly Period
        $start_date = $month_year.'-01';
        $end_date = $month_year.'-31';
        
        //Get Salesman Employee Commissions
        $result_salesman = $this->get('', " IF(".$this->table.".userid>0,'SALESMAN','') as userrolename , tblusers.name, tblusers.username, tblusers.surname, tblusers.street, tblusers.zipcode, tblusers.city, tblusers.email, ".$this->table.".userid, SUM(".$this->table.".ecommision) as sum_ecommission ",array('tblusers'=>'tblusers.userid='.$this->table.'.userid'),  "(".$this->table.".posid=0 OR ".$this->table.".posid IS NULL) AND ".$this->table.".`date` BETWEEN '".date('Y-m-d', strtotime($start_date))."' AND '". date('Y-m-d', strtotime($end_date))."'" , $this->table.'.userid');
        if($result_salesman){
            foreach($result_salesman as $k=>$rowuser){            
                $this->create_commission($month_year, $rowuser);
            }
        }
        
        //Get POS Employee Commissions   
        $result_pos = $this->get('', " IF(".$this->table.".posid>0,'POS','') as userrolename , tblusers.name, tblusers.username, tblusers.surname, tblusers.street, tblusers.zipcode, tblusers.city, tblusers.email, ".$this->table.".posid as userid, SUM(".$this->table.".ecommision) as sum_ecommission ",array('tblusers'=>'tblusers.userid='.$this->table.'.posid'),  "(".$this->table.".userid=0 OR ".$this->table.".userid IS NULL) AND ".$this->table.".`date` BETWEEN '".date('Y-m-d', strtotime($start_date))."' AND '". date('Y-m-d', strtotime($end_date))."'" , $this->table.'.posid');
        if($result_pos){
            foreach($result_pos as $k=>$rowuser){                   
                $this->create_commission($month_year, $rowuser);
            }
        }     
       
        return 1;
    }
    /**
     * Calculate Withdraw Value from Points Value
     * @return withdraw value
     */
    function withdraw_value($month_year, $rowuser){
    
        // Define Employee Commissions defined in config/constants.php
        /* Employee Commissions for Salesman 
        define('ECOMMISSION_SALESMAN_FIRST_POINTS', 1200);
        define('ECOMMISSION_SALESMAN_FIRST_CREDITS', 1500);
        define('ECOMMISSION_SALESMAN_SECOND_POINTS', 800);
        define('ECOMMISSION_SALESMAN_SECOND_CREDITS', 1000);
        define('ECOMMISSION_SALESMAN_REST_POINTS', 400);
        define('ECOMMISSION_SALESMAN_REST_CREDITS', 400);

        // Employee Commissions for Pos 
        define('ECOMMISSION_POS_FIRST_POINTS', 1200);
        define('ECOMMISSION_POS_FIRST_CREDITS', 1500);
        define('ECOMMISSION_POS_SECOND_POINTS', 800);
        define('ECOMMISSION_POS_SECOND_CREDITS', 1000);
        define('ECOMMISSION_POS_REST_POINTS', 400);
        define('ECOMMISSION_POS_REST_CREDITS', 400);*/
        
        
        /**************************************************************************************************/
        /************************************* COMMISSION CALCULATION *************************************/
        /**************************************************************************************************/
        //For Withdraw Value 
        $points1 = 0;
        $points2 = 0;
        $points3 = 0;                
        $credit_points = 0;

        $cr1 = 0;                
        if($rowuser['sum_ecommission']>=ECOMMISSION_SALESMAN_FIRST_POINTS){
            $cr1=($rowuser['sum_ecommission']-ECOMMISSION_SALESMAN_FIRST_POINTS);
            if($cr1>=0){
                $points1 = ECOMMISSION_SALESMAN_FIRST_POINTS; //1200
                $credit_points = ECOMMISSION_SALESMAN_FIRST_CREDITS; //1500 
            }
            if($cr1>=ECOMMISSION_SALESMAN_SECOND_POINTS){                       
                $c2 = floor($cr1 / ECOMMISSION_SALESMAN_SECOND_POINTS);
                $points2 = ($c2 * ECOMMISSION_SALESMAN_SECOND_POINTS); //800                        
                for($c=0;$c<$c2;$c++){
                    $credit_points = $credit_points + ECOMMISSION_SALESMAN_SECOND_CREDITS; //1000                            
                }
            }          
            $points3 = $rowuser['sum_ecommission'] - ($points1+$points2); //400
            if($points3>=ECOMMISSION_SALESMAN_REST_POINTS){
                $credit_points = $credit_points + ECOMMISSION_SALESMAN_REST_CREDITS; //400
            }                   
        }
        else if($rowuser['sum_ecommission']>=ECOMMISSION_SALESMAN_SECOND_POINTS){
            $c1=($rowuser['sum_ecommission']-ECOMMISSION_SALESMAN_SECOND_POINTS);
            if($c1>=0){
                $points2 = ECOMMISSION_SALESMAN_SECOND_POINTS; //800
                $credit_points = ECOMMISSION_SALESMAN_SECOND_CREDITS; //1000 
            }
            $points3 = $rowuser['sum_ecommission'] - ($points2); //400
            if($points3>=ECOMMISSION_SALESMAN_REST_POINTS){
                $credit_points = $credit_points + ECOMMISSION_SALESMAN_REST_CREDITS; //400
            }  
        }
        else if($rowuser['sum_ecommission']>=ECOMMISSION_SALESMAN_REST_POINTS){
            $credit_points = ECOMMISSION_SALESMAN_REST_CREDITS; //400
        }

        //Withdraw is
        $withdrawvalue = $credit_points;
        
        
        //Finally Withdraw Value >>
        //When the User has over 1200 Points between 01.02.2018 – 15.02.2018
        //Finally Withdraw is = 3900 * 1,15 (this Value is Fix)
        //$withdrawvalue = ($credit_points * 1.15);
        //Monthly Period of First 15 Days Only
        $extra_start_date = $month_year.'-01';
        $extra_end_date = $month_year.'-15';
        if($rowuser['userrolename']=='SALESMAN'){
            $extra_result = $this->get('', "SUM(".$this->table.".ecommision) as extra_sum_ecommission ",array('tblusers'=>'tblusers.userid='.$this->table.'.userid'),  "(".$this->table.".posid=0 OR ".$this->table.".posid IS NULL) AND (".$this->table.".`date` BETWEEN '".date('Y-m-d', strtotime($extra_start_date))."' AND '". date('Y-m-d', strtotime($extra_end_date))."') AND ".$this->table.".userid='".$rowuser['userid']."' " , $this->table.'.userid');
            if(isset($extra_result[0])){
                if($extra_result[0]['extra_sum_ecommission']>=ECOMMISSION_SALESMAN_EXTRA_CONDITION_POINTS){
                    $withdrawvalue = ($credit_points * ECOMMISSION_SALESMAN_EXTRA_CONDITION_CREDITS);
                }
            }
        }
        else if($rowuser['userrolename']=='POS'){
            $extra_result = $this->get('', "SUM(".$this->table.".ecommision) as extra_sum_ecommission ",array('tblusers'=>'tblusers.userid='.$this->table.'.posid'),  "(".$this->table.".userid=0 OR ".$this->table.".userid IS NULL) AND (".$this->table.".`date` BETWEEN '".date('Y-m-d', strtotime($extra_start_date))."' AND '". date('Y-m-d', strtotime($extra_end_date))."') AND ".$this->table.".posid='".$rowuser['userid']."' " , $this->table.'.posid');
            if(isset($extra_result[0])){
                if($extra_result[0]['extra_sum_ecommission']>=ECOMMISSION_POS_EXTRA_CONDITION_POINTS){
                    $withdrawvalue = ($credit_points * ECOMMISSION_POS_EXTRA_CONDITION_CREDITS);
                }
            }
        }        
        
        return $withdrawvalue;
        
        /**************************************************************************************************/
        /******************************** END COMMISSION CALCULATION **************************************/
        /**************************************************************************************************/
    }
    /* Crate Commission in db of Employee(Salesman and POS) 
     */
    function create_commission($month_year, $rowuser){
        $withdrawvalue = $this->withdraw_value($month_year, $rowuser);                
        $rc = $this->Commissionslip_model->get('','slipnr',array()," tblcommisionslips.period='".$month_year."' AND tblcommisionslips.userid='".$rowuser['userid']."' ");            
        if($rc){
            //Update commission
            $post = array('date'=>date('Y-m-d'),
                'pointsvalue'=>$rowuser['sum_ecommission'],
                'withdrawvalue'=>$withdrawvalue                 
            );

            $cid = $this->Commissionslip_model->update($post,$rc[0]['slipnr']);
            $crow = (array) $this->Commissionslip_model->get($cid, 'slipnr, slipnr_prefix, `date`, period, pointsvalue, withdrawvalue, email_sent');  
            if(count($crow)>0){
                $cdata['data'] = array_merge($rowuser, $crow, $GLOBALS['currency_data'], $GLOBALS['company_data']);            
                $this->Pdf_model->pdf_employeecommissionslip($cdata); //Create Commission Slip PDF Format
                $this->send_employeecommissionslip_email($cdata['data']); //Send Email Commission Slip 
            }
        }
        else{
            //Insert commission
            $post = array('date'=>date('Y-m-d'),
                'period'=>$month_year,
                'userid'=>$rowuser['userid'],
                'pointsvalue'=>$rowuser['sum_ecommission'],
                'withdrawvalue'=>$withdrawvalue                  
            );

            $cid = $this->Commissionslip_model->add($post);
            $crow = (array) $this->Commissionslip_model->get($cid, 'slipnr, slipnr_prefix, `date`, period, pointsvalue, withdrawvalue, email_sent');     
            if(count($crow)>0){
                $cdata['data'] = array_merge($rowuser, $crow, $GLOBALS['currency_data'], $GLOBALS['company_data']);
                $this->Pdf_model->pdf_employeecommissionslip($cdata); //Create Commission Slip PDF Format
                $this->send_employeecommissionslip_email($cdata['data']); //Send Email Commission Slip 
            }
        }
    }
    /* Send Email Comissions Slip to Employee(Salesman and POS) 
     */
    function send_employeecommissionslip_email($data){      
        $data['period'] = date('M-Y',strtotime($data['period']."-01"));  //Change Date format to send email
        $merge_fields = array();        
        $merge_fields = array_merge($merge_fields, get_employeecommissionslip_merge_fields($data));
        
        //PDF Attachment        
        $file = FCPATH.'uploads/commision_slips/'.$data['userid'].'/CommisionSlip-'.$data['slipnr_prefix'].'-'.$data['period'].'.pdf';        
        if(file_exists($file) && $file!="" && ($data['email_sent']==0 || !$data['email_sent'])){            
            
            /*$this->Email_model->add_attachment(array(
                'attachment' => $file,
                'filename' => $data['period'] . '.pdf',
                'type' => 'application/pdf',
            ));*/
            
            $this->Email_model->add_attachment(array('attachment' => $file));            
            $sent = $this->Email_model->send_email_template('new-employeecommission-slip', $data['email'], $merge_fields);

            if ($sent) {
                // Set to status sent
                $post = array('email_sent'=>1);
                $this->Commissionslip_model->update($post,$data['slipnr']);
                do_action('employeecommissionslip_sent', $data['slipnr']);
                return true;
            }     
        }
    }
    
    /* Download Commission Slip Monthly 
     */
    public function downloadslip($slipnr){
        $rowslip = $this->Commissionslip_model->get($slipnr,'userid, slipnr_prefix, period');
        $rowslip->period = date('M-Y',strtotime($rowslip->period."-1"));        
        $filepath = 'uploads/commision_slips/'.$rowslip->userid.'/CommisionSlip-'.$rowslip->slipnr_prefix.'-'.$rowslip->period.'.pdf';        
        $data = file_get_contents(base_url().$filepath); // Read the file's contents
        force_download(basename($filepath), $data);//Force Download 
    }
}
