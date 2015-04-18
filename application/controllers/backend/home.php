<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	private $auth;

	public function __construct()
    {
        parent::__construct();
        $this->my_layout->setLayout("layout/backend"); 
        $this->auth = $this->my_auth->check();
    }

	public function index()
	{
		if($this->auth == NULL) 
			$this->my_string->php_redirect(base_url().'backend/auth/login');

		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/home/index", isset($data)?$data:NULL);
	}

	public function lang($lang)
	{
		$continue = $this->input->get('continue');
		if (!empty($lang) && in_array($lang, array('jp', 'en', 'vi'))) {
			$this->session->set_userdata('_lang', $lang);
			$this->my_string->js_redirect('Chuyển đổi ngôn ngữ thành công!', !empty($continue)?base64_decode($continue):base_url().'backend');
		}
		else
			$this->my_string->js_redirect('Ngôn ngữ không tồn tại!', base_url().'backend');
	}
}

