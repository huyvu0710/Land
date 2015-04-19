<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_auth extends CI_Model{
	private $CI;
	public function __construct(){
		$this->CI =& get_instance();
	}

	public function check(){
		if(isset($_COOKIE[HHV_PREFIX.'_user_logged']) && !empty($_COOKIE[HHV_PREFIX.'_user_logged'])){
			$cookie = $_COOKIE[HHV_PREFIX.'_user_logged'];
			$cookie = json_decode($this->CI->my_string->decode_cookie($cookie), TRUE);
			$user = $this->CI->db->select('id, username, password, salt, email, fullname, groupid')->where(array('username' => $cookie['username']))->from('user')->get()->row_array();
			if(isset($user) && count($user)){
				$group = $this->CI->db->select('title, allow, group')->where(array('id' => $user['groupid']))->from('user_group')->get()->row_array();
				if ($cookie['username'] == $user['username'] && $cookie['password'] == $user['password'] && $cookie['salt'] == $user['salt']) {
					setcookie(HHV_PREFIX.'_user_logged', $this->CI->my_string->encode_cookie(json_encode(array(
						'username' => $user['username'], 
						'password' => $user['password'], 
						'salt' => $user['salt']
					))), time()+7*24*3600, '/');
					setcookie(HHV_PREFIX.'_folder', $this->CI->my_string->encode_folder($user['username']), time()+7*24*3600, '/');
					$_SESSION['username'] =  $user['username'];
					return array(
						'id' => $user['id'], 
						'username' => $user['username'], 
						'email' => $user['email'],
						'fullname' => $user['fullname'],
						'group_allow' => $group['allow'],
						'group_title' => $group['title'],
						'group_content' => $this->CI->my_string->trim_array(explode("\n", $group['group'])),
					);
				}
			}
		}
		return NULL;
	}

	/*
    ******** Permission to allow access of members group
    **********************************************/
	public function allow($auth, $url){
		// allow
		if ($auth['group_allow'] == 1) {
			if (!isset($auth['group_content']) && count($auth['group_content']) == 0) {
				$this->CI->my_string->js_redirect('Không đủ quyền truy cập!', HHV_BASE_URL.'backend');
			}
			if (in_array($url, $auth['group_content']) == FALSE) {
				$this->CI->my_string->js_redirect('Không đủ quyền truy cập!', HHV_BASE_URL.'backend');
			}
				
		}

		// disallow
		else if ($auth['group_allow'] == 0) {
			if (isset($auth['group_content']) && count($auth['group_content']) && in_array($url, $auth['group_content']) == TRUE) {
				$this->CI->my_string->js_redirect('Không đủ quyền truy cập!', HHV_BASE_URL.'backend');
			}
		}
	}
}