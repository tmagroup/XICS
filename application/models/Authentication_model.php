<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication_model extends CI_Model
{
	var $table = 'tblusers';
	var $aid = 'userid';

    public function __construct()
    {
        parent::__construct();
		$this->load->model('Userautologin_model');
        $this->autologin();
    }

   	/**
     * @param  string Username for login
     * @param  string Password
     * @param  boolean Set cookies for user if remember me is checked
     * @param  boolean
     * @return boolean if not redirect url found, if found redirect to the url
     */
    public function login($username, $password, $remember)
    {
		$_aid = $this->aid;
		$customer_login = false;

        if ((!empty($username)) and (!empty($password))) {

            $this->db->where('username', $username);
            $user = $this->db->get($this->table)->row();

            if ($user) {
                // Username is okey lets check the password now
                $this->load->helper('phpass');
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
				if (!$hasher->CheckPassword($password, $user->password)) {
                    // Password failed, return
                    return false;
                }
            } else {
				//Check Customer Login *************************
				$this->db->where('username', $username);
            	$user = $this->db->get('tblcustomers')->row();
				if ($user) {
					// Username is okey lets check the password now
					$this->load->helper('phpass');
					$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
					if (!$hasher->CheckPassword($password, $user->password)) {
						// Password failed, return
						return false;
					}

					$customer_login = true;
				} else {
	                return false;
				}
				//End Check Customer Login *************************
            }


			if($customer_login){
				$role = 'customer';
                $_aid = 'customernr';
			}else{
				$role = 'user';
			}


            if ($user->active == 0) {
                return array(
                    'memberinactive' => true,
                );
            }

			do_action('before_user_login', array(
				'username' => $username,
				'userid' => $user->$_aid,
				'role' => $role
			));
			$user_data = array(
				'user_id' => $user->$_aid,
				'logged_in' => true,
				'role' => $role
			);

            $this->session->set_userdata($user_data);

			if ($remember) {
				$this->create_autologin($user->$_aid, $role);
			}

			$this->update_login_info($user->$_aid, $role);

            return true;
        }

        return false;
    }

	/**
     * @param  boolean
     * @return none
     */
    public function logout()
    {
		$this->delete_autologin();
        if (is_logged_in()) {
            do_action('before_user_logout', get_user_id());
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('logged_in');
			$this->session->unset_userdata('role');
        }
        $this->session->sess_destroy();
	}

	/**
     * @param  integer ID to create autologin
     * @param  boolean
     * @return boolean
     */
    private function create_autologin($user_id, $role)
    {
        $this->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->config->item('sess_cookie_name')))), 0, 16);
        $this->userautologin->delete($user_id, $key, $role);
        if ($this->userautologin->set($user_id, md5($key), $role)) {
            set_cookie(array(
                'name' => 'autologin',
                'value' => serialize(array(
                    'user_id' => $user_id,
                    'key' => $key,
					'role' => $role
                )),
                'expire' => 60 * 60 * 24 * 31 * 2, // 2 months
            ));

            return true;
        }

        return false;
    }

	/**
     * @param  boolean
     * @return none
     */
    private function delete_autologin()
    {
        $this->load->helper('cookie');
        if ($cookie = get_cookie('autologin', true)) {
            $data = unserialize($cookie);
            $this->userautologin->delete($data['user_id'], md5($data['key']), $data['role']);
            delete_cookie('autologin');
        }
    }

	/**
     * @return boolean
     * Check if autologin found
     */
    public function autologin()
    {
        if (!is_logged_in()) {
            $this->load->helper('cookie');
            if ($cookie = get_cookie('autologin', true)) {
                $data = unserialize($cookie);
                if (isset($data['key']) and isset($data['user_id']) and isset($data['role'])) {
                    if (!is_null($user = $this->userautologin->get($data['user_id'], md5($data['key']), $data['role']))) {
                        // Login user
                        $user_data = array(
							'user_id' => $user->id,
							'logged_in' => true,
							'role' => $role
						);
                        $this->session->set_userdata($user_data);

                        // Renew users cookie to prevent it from expiring
                        set_cookie(array(
                            'name' => 'autologin',
                            'value' => $cookie,
                            'expire' => 60 * 60 * 24 * 31 * 2, // 2 months
                        ));
                        $this->update_login_info($user->id, $role);
                        return true;
                    }
                }
            }
        }

        return false;
    }

	/**
     * @param  integer ID
     * @param  boolean
     * @return none
     * Update login info on autologin
     */
    private function update_login_info($user_id, $role)
    {
		if($role=='customer'){
			$this->db->set('last_ip', $this->input->ip_address());
			$this->db->set('last_login', date('Y-m-d H:i:s'));
			$this->db->where('customernr', $user_id);
			$this->db->update('tblcustomers');
		}
		else{
			$_aid = $this->aid;
			$this->db->set('last_ip', $this->input->ip_address());
			$this->db->set('last_login', date('Y-m-d H:i:s'));
			$this->db->where($_aid, $user_id);
			$this->db->update($this->table);
		}
    }
}
