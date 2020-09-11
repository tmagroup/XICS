<?php
defined('BASEPATH') or exit('No direct script access allowed');
class History_model extends CI_Model
{
    var $table = 'tblhistories';
    var $aid = 'historienr';
    
    public function __construct()
    {
        parent::__construct();            
    }

    /**
     * Check if History
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
            $ticket = $this->db->get($this->table)->row();
            if ($ticket) {
                $ticket->attachments = $this->get_ticket_attachments($id);
            }
            return $ticket;
        }
        
        return $this->db->get($this->table)->result_array();
    }
    
    
    /**
     * Add new history
     * @param array $data assignment $_POST data
     */
    public function add($data)
    {  
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        
        if(get_user_role()=='customer'){
            $data['usertype'] = 'customer';
        }
        else{
            $data['usertype'] = 'user';
        }
    
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){ 
            //Add ID Prefix
            $dataId = array();
            $dataId['historienr_prefix'] = idprefix('history',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
        }
        
        return $id;
    }
}
