<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Infodocument_model extends CI_Model
{
    var $table = 'tblinfodocuments';
    var $aid = 'documentnr';
	
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
        
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Info Document Added [ID: ' . $id . ', Document Nr: '.$data['documentnr'].', Document Title: ' . $data['documenttitle'] . ']');         
            
            //History 
            $Action_data = array('actionname'=>'infodocument', 'actionid'=>$id, 'actiontitle'=>'infodocument_added');
            do_action_history($Action_data);
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
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        
        if ($this->db->affected_rows() > 0) {            
            //Log Activity
            logActivity('Info Document Updated [ID: ' . $id . ', Document Nr: '.$data['documentnr'].', Document Title: ' . $data['documenttitle'] . ']');
            
            //History 
            $Action_data = array('actionname'=>'infodocument', 'actionid'=>$id, 'actiontitle'=>'infodocument_updated');
            do_action_history($Action_data);
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
        $rowfield = $this->get($id,'documentnr, documenttitle');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
	logActivity('Info Document Deleted [ID: ' . $id . ', Document Nr: '.$rowfield->documentnr.', Document Title: '.$rowfield->documenttitle.']');
        
        //History 
        $Action_data = array('actionname'=>'infodocument', 'actionid'=>$id, 'actiontitle'=>'infodocument_deleted');
        do_action_history($Action_data);  
        
        return 1;
    }
}