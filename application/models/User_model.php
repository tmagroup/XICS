<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends CI_Model
{
    var $table = 'tblusers';
    var $aid = 'userid';
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
    }

    /**
     * Check if user
     * @param  mixed $userid 
     * @return mixed
     */
    public function get($id='', $field='', $where="")
    {     
		//Select   
        if($field!=""){
            $this->db->select($field);
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
     * Add new user
     * @param array $data user $_POST data
     */
    public function add($data)
    {
        //Check Username
        $this->db->where('username', trim($data['username']));
        $username = $this->db->get($this->table)->row();
        if ($username) {            
            return lang('page_form_validation_username_already_exists');
        }
        
        //Check Email
        $this->db->where('email', trim($data['email']));
        $email = $this->db->get($this->table)->row();
        if ($email) {
            return lang('page_form_validation_email_already_exists');
        }
        
        
        //Check Customer Username
        $this->db->where('username', trim($data['username']));
        $c_username = $this->db->get('tblcustomers')->row();
        if ($c_username) {            
            return lang('page_form_validation_username_already_exists');
        }
        
        //Check Customer Email
        $this->db->where('email', trim($data['email']));
        $c_email = $this->db->get('tblcustomers')->row();
        if ($c_email) {
            return lang('page_form_validation_email_already_exists');
        }
        
        
        
        //Password Encrpted
        $this->load->helper('phpass');
        $original_password = $data['password'];        
        $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
        $data['password'] = $hasher->HashPassword($data['password']);
       
        //Unnecessory data
        unset($data['cpassword']);
        
        
        
        //Google Calendar IDs for Master Admin Multiple Account Allow OR Single Calendar ID
        //$data['googleCalendarIDs'] = implode(',',$data['googleCalendarIDs']);
        
        
        //Google Calendar IDs for Master Admin Multiple Account Allow OR Single Calendar ID
        $googleCalendarIDs = '';
        if($data['userrole']==1 || $data['userrole']==5){
            if(isset($data['googleCalendarIDs'])){
                $googleCalendarIDs = implode(',',$data['googleCalendarIDs']);
            }
            if(isset($data['googleCalendarIDs2'])){
                unset($data['googleCalendarIDs2']);
            }
        }
        else{
            if(isset($data['googleCalendarIDs2'])){
                $googleCalendarIDs = implode(',',$data['googleCalendarIDs2']);
                unset($data['googleCalendarIDs2']);
            }
        }
        $data['googleCalendarIDs'] = $googleCalendarIDs;
        
        
        //Database data
        $data1 = $data;
        unset($data['Permission']);   
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){
            //User Permissions
            foreach($data1['Permission']['permissionid'] as $permissionid){
                $this->db->insert('tbluserpermissions', array(
                    'permissionid' => $permissionid,
                    'userid' => $id,
                    'can_view' => isset($data1['Permission']['can_view'][$permissionid])?$data1['Permission']['can_view'][$permissionid]:0,
                    'can_view_own' => isset($data1['Permission']['can_view_own'][$permissionid])?$data1['Permission']['can_view_own'][$permissionid]:0,
                    'can_create' => isset($data1['Permission']['can_create'][$permissionid])?$data1['Permission']['can_create'][$permissionid]:0,
                    'can_edit' => isset($data1['Permission']['can_edit'][$permissionid])?$data1['Permission']['can_edit'][$permissionid]:0,
                    'can_delete' => isset($data1['Permission']['can_delete'][$permissionid])?$data1['Permission']['can_delete'][$permissionid]:0,
                    'can_import' => isset($data1['Permission']['can_import'][$permissionid])?$data1['Permission']['can_import'][$permissionid]:0,
                ));
            }
            
            //Log Activity
            logActivity('New User Added [ID: ' . $id . ', ' . $data['name'] . ']');
            
            //Add ID Prefix
            $dataId = array();
            $dataId['userid_prefix'] = idprefix('user',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
        }
        
        return $id;
    }
    
    /**
     * Update user
     * @param  array $data user
     * @param  mixed $id   user id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Check Username
        if(isset($data['username'])){
            $this->db->where($this->aid.'!=', $id);
            $this->db->where('username', trim($data['username']));
            $username = $this->db->get($this->table)->row();
            if ($username) {            
                return lang('page_form_validation_username_already_exists');
            }
        }
        
        //Check Email
        if(isset($data['email'])){
            $this->db->where($this->aid.'!=', $id);
            $this->db->where('email', trim($data['email']));
            $email = $this->db->get($this->table)->row();
            if ($email) {
                return lang('page_form_validation_email_already_exists');
            }
        }
        
        //Check Customer Username
        if(isset($data['username'])){
            $this->db->where('username', trim($data['username']));
            $c_username = $this->db->get('tblcustomers')->row();
            if ($c_username) {            
                return lang('page_form_validation_username_already_exists');
            }
        }
        
        //Check Customer Email
        if(isset($data['email'])){
            $this->db->where('email', trim($data['email']));
            $c_email = $this->db->get('tblcustomers')->row();
            if ($c_email) {
                return lang('page_form_validation_email_already_exists');
            }
        }
        
        //Password Encrpted
        if($data['password']!=""){
            $this->load->helper('phpass');
            $original_password = $data['password'];        
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $data['password'] = $hasher->HashPassword($data['password']);
        }else{
            unset($data['password']);
        }
       
        //Unnecessory data
        unset($data['cpassword']);
        
        
        //Google Calendar IDs for Master Admin Multiple Account Allow OR Single Calendar ID
        if($data['userrole']==1 || $data['userrole']==5){
            if(isset($data['googleCalendarIDs'])){
                $data['googleCalendarIDs'] = implode(',',$data['googleCalendarIDs']);
            }
            if(isset($data['googleCalendarIDs2'])){
                unset($data['googleCalendarIDs2']);
            }
        }
        else{
            if(isset($data['googleCalendarIDs2'])){
                $data['googleCalendarIDs'] = implode(',',$data['googleCalendarIDs2']);
                unset($data['googleCalendarIDs2']);
            }
        }
        
        
        //Database data
        $data1 = $data;
        unset($data['Permission']);     
        $data['updated'] = date('Y-m-d H:i:s');
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('User Updated [ID: ' . $id . ', ' . $data['name'] . ']');
        } 
                
        if($id>0){
            //User Permissions
            if(isset($data1['Permission'])){
                foreach($data1['Permission']['permissionid'] as $permissionid){
                    $this->db->where('permissionid', $permissionid);
                    $this->db->where('userid', $id);
                    $perm = $this->db->get('tbluserpermissions')->row();
                    if($perm){                                     
                        $this->db->where('permissionid', $permissionid);
                        $this->db->where('userid', $id);
                        $this->db->update('tbluserpermissions', array(
                            'can_view' => isset($data1['Permission']['can_view'][$permissionid])?$data1['Permission']['can_view'][$permissionid]:0,
                            'can_view_own' => isset($data1['Permission']['can_view_own'][$permissionid])?$data1['Permission']['can_view_own'][$permissionid]:0,
                            'can_create' => isset($data1['Permission']['can_create'][$permissionid])?$data1['Permission']['can_create'][$permissionid]:0,
                            'can_edit' => isset($data1['Permission']['can_edit'][$permissionid])?$data1['Permission']['can_edit'][$permissionid]:0,
                            'can_delete' => isset($data1['Permission']['can_delete'][$permissionid])?$data1['Permission']['can_delete'][$permissionid]:0,
                            'can_import' => isset($data1['Permission']['can_import'][$permissionid])?$data1['Permission']['can_import'][$permissionid]:0,
                        ));
                    }else{
                        $this->db->insert('tbluserpermissions', array(
                            'permissionid' => $permissionid,
                            'userid' => $id,
                            'can_view' => isset($data1['Permission']['can_view'][$permissionid])?$data1['Permission']['can_view'][$permissionid]:0,
                            'can_view_own' => isset($data1['Permission']['can_view_own'][$permissionid])?$data1['Permission']['can_view_own'][$permissionid]:0,
                            'can_create' => isset($data1['Permission']['can_create'][$permissionid])?$data1['Permission']['can_create'][$permissionid]:0,
                            'can_edit' => isset($data1['Permission']['can_edit'][$permissionid])?$data1['Permission']['can_edit'][$permissionid]:0,
                            'can_delete' => isset($data1['Permission']['can_delete'][$permissionid])?$data1['Permission']['can_delete'][$permissionid]:0,
                            'can_import' => isset($data1['Permission']['can_import'][$permissionid])?$data1['Permission']['can_import'][$permissionid]:0,
                        ));
                    }  
                }
            }
        }
        
        return $id;
    }    
    
    /**
     * Delete user
     * @param  array $data user
     * @param  mixed $id   user id
     * @return boolean
     */
    public function delete($id)
    {
        //1 is Master Admin can never delete
        if($id==1){
            return lang('master_admin_can_never_delete');
        }
        //Delete User Profile Image
        $name = get_user_name($id);
        handle_user_profile_image_delete($id);        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('User Deleted [ID: ' . $id . ', ' . $name . ']');
        
        return 1;
    }    
    
    /** Get User Permissions
     */
    public function get_user_permissions($id)
    {
        $permissions = $this->object_cache->get('user-'.$id.'-permissions');

        if(!$permissions && !is_array($permissions)){
            $this->db->select('tbluserpermissions.*,tblpermissions.shortname as permission_name');
            $this->db->join('tblpermissions', 'tblpermissions.permissionid = tbluserpermissions.permissionid');
            $this->db->where('userid', $id);
            $permissions = $this->db->get('tbluserpermissions')->result();
            $this->object_cache->add('user-'.$id.'-permissions', $permissions);
        }
        return $permissions;
    }    
}
