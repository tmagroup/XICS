<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Quotationreminderopen_model extends CI_Model
{
    var $table = 'tblquotationreminderopen';
    var $aid = 'track_id';
	
    public function __construct()
    {
        parent::__construct();     
        $this->load->model('User_model');
    }
    
    /**
     * Check if reminder
     * @param  mixed $remindernr 
     * @return mixed
     */
    public function get($id='', $field='', $join=array(), $where="", $orderby="", $limit="")
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
                
        //Order By
        if($orderby!=""){
            $this->db->order_by($orderby, 'DESC');
        }
        
        //Where 
        if($where!=""){           
            $this->db->where($where);           
        }
        
        //Limit
        if($limit!=""){  
            $this->db->limit($limit);
        }
        
        if (is_numeric($id)) {
            $this->db->where($this->table.".".$this->aid, $id);
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new reminder
     * @param array $data reminder $_POST data
     */
    public function add($data)
    {    
        //Database data
        $data['senddate'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){                        
            $rowfield = $this->User_model->get($data['responsible'],'name');
            $name = isset($rowfield->name)?$rowfield->name:'';
            logActivity('Quotation Reminder Open Sent [ID: ' . $id . ', No: '.$data['sendno'].', Quotation: ' . $data['quotationnr'] . ', Responsible: ' . $name . ']');         
        }
        
        return $id;
    }
}