 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller 
{
	private $auth;

	public function __construct()
    {
        parent::__construct();
        $this->my_layout->setLayout("layout/backend");
        $this->auth = $this->my_auth->check();
        if($this->auth == NULL) 
			$this->my_string->php_redirect(base_url().'backend');

		/***
		* Permission to access
		***/
		$this->my_auth->allow($this->auth, 'backend/contact');
    }

    /*
    ******** contact
    **********************************************/
    public function index($page = 1)
	{
		$this->my_auth->allow($this->auth, 'backend/contact/index');

		$keyword = $this->input->get('keyword');
		$sort = $this->my_common->sort_orderby($this->input->get('sort_field'), $this->input->get('sort_value'));
		$config = $this->my_common->backend_pagination();
		$config['base_url'] = base_url().'backend/contact/index';
		$config['per_page'] = 10;
		
		/***
		* Load pagination when search
		***/
		if(!empty($keyword)){
			$config['total_rows'] = $this->db->from('contact')->like('fullname', $keyword)->count_all_results();
		}
		else{
			$config['total_rows'] = $this->db->from('contact')->count_all_results();
		}

		// Trang này ko có data. Load trang trước của nó
        $_totalpage = ceil($config['total_rows']/$config['per_page']);
        $page = ($page > $_totalpage)?$_totalpage:$page;

		$config['uri_segment'] = 4; 
		$config['suffix'] = (isset($sort) && count($sort))?'?sort_field='.$sort['field'].'&sort_value='.$sort['value']:'';
		$config['suffix'] = $config['suffix'].(!empty($keyword)?'&keyword='.$keyword:'');
		$config['first_url'] = $config['base_url'].$config['suffix'];
		if ($config['total_rows'] > 0) {
			$this->pagination->initialize($config); 

			/***
			* Create pagination
			***/
			$data['data']['pagination'] = $this->pagination->create_links();

			/***
			* Load data when search - follow 'title'
			***/
			if(!empty($keyword)){
				$data['data']['_list'] = $this->db->from('contact')->like('fullname', $keyword)->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
			}
			else{
				$data['data']['_list'] = $this->db->from('contact')->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
			}
		}
		$data['data']['_config'] = $config;
		$data['data']['_page'] = $page;
		$data['data']['_sort'] = $sort;
		$data['data']['_keyword'] = $keyword;
		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/contact/index", isset($data)?$data:NULL);
	}

	/*
    ******** View contactertise
    **********************************************/
	public function view($id)
	{
		$this->my_auth->allow($this->auth, 'backend/contact/view');

		$data['data'] = $this->db->where(array('id' => $id))->from('contact')->get()->row_array();
		$data['data'] = $this->my_string->allow_post($data['data'], array('fullname', 'email', 'phone', 'message'));
		$data['data']['readed'] = 1;
		$this->db->where(array('id' => $id))->update('contact', $data['data']);

        $data['data']['auth'] = $this->auth;
        $this->my_layout->view("backend/contact/view", isset($data)?$data:NULL);
	}

	/*
    ******** Delete contact
    **********************************************/
	public function del($id)
	{
		$this->my_auth->allow($this->auth, 'backend/contact/del');
		
		$id = (int)$id;
		$continue = $this->input->get('continue');
		$contact = $this->db->where(array('id' => $id))->from('contact')->get()->row_array();
		if(!isset($contact) || count($contact) == 0)
			$this->my_string->php_redirect(base_url().'backend');
		$this->db->delete('contact', array('id' => $id)); 
		$this->my_string->js_redirect('Xóa bài viết liên hệ thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/contact/index');
	}

	/*
    ******** Set readed
    **********************************************/
	public function set($field, $id)
	{
		$this->my_auth->allow($this->auth, 'backend/contact/set');
		$id = (int)$id;
		$continue = $this->input->get('continue');
		$contact = $this->db->where(array('id' => $id))->from('contact')->get()->row_array();
		if(!isset($contact) || count($contact) == 0)
			$this->my_string->php_redirect(base_url().'backend/contact/index');
		if(!isset($contact[$field]))
			$this->my_string->php_redirect(base_url().'backend/contact/index');
		$this->db->where(array('id' => $id))->update('contact', array($field => (($contact[$field] == 1)?0:1)));
		$this->my_string->js_redirect('Thay đổi tình trạng thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/contact/index');
	}
}