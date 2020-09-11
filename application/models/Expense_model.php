<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Expense_model extends CI_Model
{
    var $table = 'tblexpenses';
    var $aid = 'expenseid';
    
    public function __construct()
    {
        parent::__construct();        
    }

    /**
     * Check if Expense
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
     * Add new Expense
     * @param array $data Expense $_POST data
     */
    public function add($data)
    {         
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        $data['start'] = to_sql_date($data['start'], true); 
        $data['end'] = to_sql_date($data['end'], true); 
        
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){   
            //Log Activity
            logActivity('New Expense Added [ID: ' . $id . ']');
        }
        
        return $id;
    }
    
    /**
     * Update Expense
     * @param  array $data Expense
     * @param  mixed $id   Expense id
     * @return boolean
     */
    public function update($data, $id)
    {    
        //Database data
        if(isset($data['start'])){
            $data['start'] = to_sql_date($data['start'], true); 
        }
        if(isset($data['end'])){
            $data['end'] = to_sql_date($data['end'], true); 
        }
        
        $this->db->where($this->aid, $id);     
        $this->db->update($this->table, $data);
        
        if ($this->db->affected_rows() > 0) {
            $this->db->query("UPDATE ".$this->table." SET `updated`='".date('Y-m-d H:i:s')."' WHERE ".$this->aid."='".$id."' ");
            
            //Log Activity
            logActivity('Expense Updated [ID: ' . $id . ']');
        } 
        
        return $id;
    }  
    
    /**
     * Delete Expense
     * @param  array $data Expense
     * @param  mixed $id   Expense id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Expense Deleted [ID: ' . $id .']');
        
        return 1;
    } 
}
