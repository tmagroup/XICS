<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Userautologin_model extends CI_Model
{
    var $table = 'tblusers';
    var $aid = 'userid';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if autologin found
     * @param  mixed $user_id 
     * @param  string $key     key from cookie to retrieve from database
     * @return mixed
     */
    public function get($user_id, $key, $role)
    {
        // check if user
        $this->db->where('user_id', $user_id);
        $this->db->where('key_id', $key);
        $this->db->where('role', $role);
        $user = $this->db->get('tbluserautologin')->row();
        if (!$user) {
            return null;
        }        
        
        if($role=='customer'){
            $table = 'tblcustomers';		
            $aid = 'customernr';
        }
        else{
            $table = $this->table;
            $aid = $this->aid;
        }
        
        $this->db->select($table . '.' . $aid);
        $this->db->from($table);
        $this->db->join('tbluserautologin', 'tbluserautologin.user_id = ' . $table . '.' . $aid);
        
        $this->db->where('tbluserautologin.user_id', $user_id);
        $this->db->where('tbluserautologin.key_id', $key);
        $this->db->where('tbluserautologin.role', $role);
        $query = $this->db->get();
        if ($query) {
            if ($query->num_rows() == 1) {
                $user        = $query->row();                
                return $user;
            }
        }

        return null;
    }

    /**
     * Set new autologin if user have clicked remember me
     * @param mixed $user_id 
     * @param string $key     cookie key
     * @param integer 
     */
    public function set($user_id, $key, $role)
    {
        return $this->db->insert('tbluserautologin', array(
            'user_id' => $user_id,
            'key_id' => $key,
            'role' => $role,
            'user_agent' => substr($this->input->user_agent(), 0, 149),
            'last_ip' => $this->input->ip_address()
        ));
    }

    /**
     * Delete user autologin
     * @param  mixed $user_id 
     * @param  string $key     cookie key
     * @param integer 
     */
    public function delete($user_id, $key, $role)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('key_id', $key);
        $this->db->where('role', $role);
        $this->db->delete('tbluserautologin');
    }
}
