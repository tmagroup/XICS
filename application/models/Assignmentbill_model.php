<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Assignmentbill_model extends CI_Model
{
    var $table = 'tblbills';
    var $aid = 'billnr';
	
    public function __construct()
    {
        parent::__construct(); 
    }

    /**
     * Check if reminder
     * @param  mixed $remindernr 
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
     * Add new reminder
     * @param array $data reminder $_POST data
     */
    public function add($data)
    {    
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();                
        $data['monthyear'] = $data['y_monthyear']."-".$data['m_monthyear'];
        unset($data['m_monthyear']);
        unset($data['y_monthyear']);
        
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Assignment Invoice Added [ID: ' . $id . ', Assignment Nr: '.$data['assignmentnr'].', Invoice Nr: ' . $data['invoicenr'] . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update reminder
     * @param  array $data reminder
     * @param  mixed $id   reminder id
     * @return boolean
     */
    public function update($data, $id, $popup=false)
    {    
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        $data['monthyear'] = $data['y_monthyear']."-".$data['m_monthyear'];
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        
        if ($this->db->affected_rows() > 0) {            
            //Log Activity
            logActivity('Assignment Invoice Updated [ID: ' . $id . ', Assignment Nr: '.$data['assignmentnr'].', Invoice Nr: ' . $data['invoicenr'] . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete reminder
     * @param  array $data reminder
     * @param  mixed $id   reminder id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Leadnr
        $rowfield = $this->get($id,'assignmentnr, invoicenr');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
	logActivity('Assignment Reminder Deleted [ID: ' . $id . ', Assignment Nr: '.$rowfield->assignmentnr.', Invoice Nr: '.$rowfield->invoicenr.']');
        
        return 1;
    }
}