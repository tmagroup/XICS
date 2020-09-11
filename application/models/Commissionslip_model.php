<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Commissionslip_model extends CI_Model
{
    var $table = 'tblcommisionslips';
    var $aid = 'slipnr';
    
    public function __construct()
    {
        parent::__construct();
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
        
        if (is_numeric($id)) {
            $this->db->where($this->aid, $id);
            
            return $this->db->get($this->table)->row();
        }
        
        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new Commission slip
     * @param array $data Commission slip $_POST data
     */
    public function add($data)
    {                
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Commission Slip Added [ID: ' . $id .']');
            
            //Add ID Prefix
            $dataId = array();
            $dataId['slipnr_prefix'] = idprefix('commisionslip',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
        }
        
        return $id;
    }
    
    /**
     * Update Commissionslip
     * @param  array $data Commission slip
     * @param  mixed $slipnr  
     * @return boolean
     */
    public function update($data, $id)
    {       
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Commission Slip Updated [ID: ' . $id . ']');
        } 
        
        return $id;
    }    
}
