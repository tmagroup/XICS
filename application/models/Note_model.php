<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Note_model extends CI_Model
{
    var $table = 'tblnotes';
    var $aid = 'id';
	
    public function __construct()
    {
        parent::__construct();  
    }

    /**
     * Check if note
     * @param  mixed $notenr 
     * @return mixed
     */
    public function get($id='', $field='', $join=array(), $where="", $groupby="", $orderby="")
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
        
        //Order By
        if($orderby!=""){
            $this->db->order_by($orderby);
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
     * Add new note
     * @param array $data note $_POST data
     */
    public function add($data)
    {    
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['addedfrom'] = get_user_id();        
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            $rowfield = $this->get($id,'description');            
            logActivity('New Comment Added [ID: ' . $id . ', Comment: ' . $rowfield->description . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update note
     * @param  array $data note
     * @param  mixed $id   note id
     * @return boolean
     */
    public function update($data, $id)
    {    
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');                
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        
        if ($this->db->affected_rows() > 0) {            
            $rowfield = $this->get($id,'description'); 
            logActivity('Comment Updated [ID: ' . $id . ', Comment: ' . $rowfield->description . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete note
     * @param  array $data note
     * @param  mixed $id   note id
     * @return boolean
     */
    public function delete($id)
    {
        //Get description
        $rowfield = $this->get($id,'description');
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        logActivity('Comment Deleted [ID: ' . $id . ', Comment: ' . $rowfield->description . ']');
        return 1;
    }
}