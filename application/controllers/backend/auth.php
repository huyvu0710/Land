<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller {

	private $auth;

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');

        $this->auth = $this->my_auth->check();
        
    }

    /*
    ******** Login
    **********************************************/
	public function login()
	{
		if($this->auth != NULL) 
			$this->my_string->php_redirect(HHV_BASE_URL.'backend');
		$data['seo']['title'] = "Đăng nhập vào hệ thống";
		if($this->input->post('login')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[username]', 'Tên tài khoản', 'trim|required|min_length[3]|max_length[20]|regex_match[/^([a-z0-9_])+$/i]|callback__username');
			$this->form_validation->set_rules('data[password]', 'Mật khẩu', 'trim|required|callback__password[' .$_post['username']. ']');
			if ($this->form_validation->run() == TRUE)
			{
				$_post = $this->my_string->allow_post($_post, array('username', 'password'));
				$user = $this->db->select('username, password, salt')->where(array('username' => $_post['username']))->from('user')->get()->row_array();
				setcookie(HHV_PREFIX.'_user_logged', $this->my_string->encode_cookie(json_encode($user)), time()+7*24*3600);
				$this->db->where(array('username' => $_post['username']))->update('user', array('logined' => gmdate('Y-m-d H:i:s', time() + 7*3600), 'ip_logging' => $_SERVER['SERVER_ADDR'])); 
				$this->my_string->js_redirect('Đăng nhập vào hệ thống thành công!', HHV_BASE_URL.'backend/auth/login');
			}
		}
		$this->load->view('backend/auth/login', $data);
	}

	/***
	* Set validate to username
	***/
	public function _username($username){
		$count = $this->db->where(array('username' => $username))->from('user')->count_all_results();
		if($count == 0){
			$this->form_validation->set_message('_username', '%s không tồn tại!');
			return FALSE;
		}
		return TRUE;
	}

	/***
	* Set validate to password
	***/
	public function _password($password, $username){
		if($this->_username($username) == TRUE){
			$user = $this->db->select('username, password, salt')->where(array('username' => $username))->from('user')->get()->row_array();
			$password = $this->my_string->encode_password($user['username'], $password, $user['salt']);
			if($password != $user['password']){
				$this->form_validation->set_message('_password', '%s không hợp lệ!');
				return FALSE;
			}
			return TRUE;
		}
	}

	/*
    ******** Logout
    **********************************************/
	public function logout()
	{
		if($this->auth == NULL) 
			$this->my_string->php_redirect(HHV_BASE_URL.'backend');
		delete_cookie(HHV_PREFIX.'_user_logged');
		setcookie(HHV_PREFIX.'_user_logged', NULL, time()-3600);
		$this->my_string->php_redirect(HHV_BASE_URL.'backend');
	}

	/*
    ******** Forgot password
    **********************************************/
	public function forgot()
	{
		if($this->auth != NULL) 
			$this->my_string->php_redirect(HHV_BASE_URL.'backend');
		$data['seo']['title'] = "Quên thông tin tài khoản";
		if($this->input->post('forgot')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[email]', 'Email', 'trim|required|valid_email|callback__email');
			if ($this->form_validation->run() == TRUE)
			{
				$_code = $this->my_string->random(69, TRUE);
				$_post = $this->my_string->allow_post($_post, array('email'));
				$this->db->where(array('email' => $_post['email']))->update('user', array(
					'forgot_time_expired' =>time() + 3600, 
					'forgot_code' => $_code,
					)); 
				$this->my_common->sentmail(array(
					'name' => 'HuyVu',
					'from' => 'hahuyvu0710@gmail.com',
					'password' => 'tnjafqmehutpzjxe',
					'to' => $_post['email'],
					'subject' => 'Mã xác nhận quên thông tin tài khoản '.$_post['email'],
					'message' => 'Click vào link bên dưới để nhận lại mật khẩu mới : '.HHV_BASE_URL.'backend/auth/reset/?email='.urlencode($_post['email']).'&code='.urlencode($_code)
				));
				$this->my_string->js_redirect('Gửi mã xác nhận vào Mail thành công!', HHV_BASE_URL.'backend');
			}
		}
		$this->load->view('backend/auth/forgot', $data);
	}

	/*
    ******** Reset password
    **********************************************/
	public function reset()
	{
		if($this->auth != NULL) 
			$this->my_string->php_redirect(HHV_BASE_URL.'backend');
		$email = $this->input->get('email');
		$code = $this->input->get('code');
		if(isset($email) && !empty($email) && isset($code) && !empty($code)){
			$_password = '';
			$user = $this->db->select('username, forgot_code, forgot_time_expired')->from('user')->where(array('email' => $email, 'forgot_code' => $code))->get()->row_array();
			if(!isset($user) || count($user) == 0 )
				$this->my_string->js_redirect('Email hoặc mã xác nhận không hợp lệ!', HHV_BASE_URL.'backend');
			if($user['forgot_time_expired'] <= time())
				$this->my_string->js_redirect('Mã xác nhận đã hết hạn!', HHV_BASE_URL.'backend');

			$_post['password'] = $this->my_string->random(5, TRUE);
			$_password = $_post['password'];
			$_post['salt'] = $this->my_string->random(69, TRUE);
			$_post['password'] = $this->my_string->encode_password($user['username'], $_post['password'], $_post['salt']);
			$_post['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
			$_post['forgot_code'] = '';
			$_post['forgot_time_expired'] = '';

			$this->db->where(array('username' => $user['username']))->update('user', $_post); 
			
			$this->my_common->sentmail(array(
				'name' => 'HHV',
				'from' => 'hahuyvu0710@gmail.com',
				'password' => 'tnjafqmehutpzjxe',
				'to' => $email,
				'subject' => 'Mật khẩu mới cho tài khoản '.$user['username'],
				'message' => '<b>Thông tin tài khoản của bạn:</b> <br />Username: ' .$user['username']. '<br />Password: ' .$_password. '<br /><i>Sau khi đăng nhập thành công, bạn nên thay đổi mật khẩu lại! Thân!</i>'
			));
			$this->my_string->js_redirect('Gửi mật khẩu mới vào Mail thành công!', HHV_BASE_URL.'backend');
		}
		else{
			$this->my_string->js_redirect('Email hoặc mã xác nhận không hợp lệ!', HHV_BASE_URL.'backend');
		}
	}

	/***
	* Set validate to email
	***/
	public function _email($email){
		$count = $this->db->from('user')->where(array('email' => $email))->count_all_results();
		if($count == 0){
			$this->form_validation->set_message('email', '%s không tồn tại!');
			return FALSE;
		}
		return TRUE;
	}

	/*
    ******** Create new account admin
    **********************************************/
	public function create_manager()
	{
		$count = $this->db->from('user')->count_all_results();
		if($count >= 1){
			$this->my_string->php_redirect(HHV_BASE_URL.'backend');
		}
		$data['seo']['title'] = "Tạo tài khoản quản trị";
		if($this->input->post('create')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[username]', 'Tên tài khoản', 'trim|required|min_length[3]|max_length[20]|regex_match[/^([a-z0-9_])+$/i]');
			$this->form_validation->set_rules('data[password]', 'Mật khẩu', 'trim|required');
			$this->form_validation->set_rules('data[repassword]', 'Xác nhận mật khẩu', 'trim|required||matches[data[password]]');
			$this->form_validation->set_rules('data[email]', 'Email', 'trim|required|valid_email');
			if ($this->form_validation->run() == TRUE)
			{
				$_post = $this->my_string->allow_post($_post, array('username', 'password', 'email'));
				$_post['salt'] = $this->my_string->random(69, TRUE);
				$_post['password'] = $this->my_string->encode_password($_post['username'], $_post['password'], $_post['salt']);
				$_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				
				$this->db->insert('user', $_post); 
				$this->my_string->js_redirect('Tạo tài khoản quản trị thành công!', HHV_BASE_URL.'backend/auth/login');
			}
		}
		$this->load->view('backend/auth/create_manager', $data);
	}
}

