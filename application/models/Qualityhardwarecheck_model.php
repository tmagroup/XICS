<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Qualityhardwarecheck_model extends CI_Model
{
    var $table = 'tblqualityhardwarechecks';
    var $aid = 'qualitychecknr';
    
    public function __construct()
    {
        parent::__construct();        
    }

    /**
     * Check if Quality Check
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
     * Add new Quality Check
     * @param array $data Quality Check $_POST data
     */
    public function add($data)
    {  
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){   
            //Add ID Prefix
            $dataId = array();
            $dataId['qualitychecknr_prefix'] = idprefix('hardwarequalitycheck',$id);            
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
            
            //Get Qualitychecknr
            $rowfield = $this->get($id,'qualitychecknr_prefix');        
            //Log Activity
            logActivity('New Hardware Quality Check Added [ID: ' . $id . ', ' . $rowfield->qualitychecknr_prefix . ']');
        }
        
        return $id;
    }
    
    /**
     * Update Quality Check
     * @param  array $data Quality Check
     * @param  mixed $id   Quality Check id
     * @return boolean
     */
    public function update($data, $id)
    {    
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        $this->db->where($this->aid, $id);     
        $this->db->update($this->table, $data);
        
        if ($this->db->affected_rows() > 0) {
            //Get Qualitychecknr
            $rowfield = $this->get($id,'qualitychecknr_prefix');        
            //Log Activity
            logActivity('Hardware Quality Check Updated [ID: ' . $id . ', ' . $rowfield->qualitychecknr_prefix . ']');
        } 
        
        return $id;
    }  
}
