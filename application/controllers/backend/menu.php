<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Controller 
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
		$this->my_auth->allow($this->auth, 'backend/menu');
    }

    /*
    ******** Advertise
    **********************************************/
    public function index($page = 1)
	{
		$this->my_auth->allow($this->auth, 'backend/menu/index');

		/***
		* Sort - follow input location
		***/
		if($this->input->post('sort')){
			$_order = $this->input->post('order');
			if (isset($_order) && count($_order)) {
				foreach ($_order as $keyOrder => $valOrder) {
	                $_data[] = array(
	                  'id' => $keyOrder ,
	                  'order' => (int)$valOrder ,
	                  // 'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600),
	                );
	            }
	            $this->db->update_batch('menu', $_data, 'id'); 
	            $this->my_string->js_reload('Cập nhật vị trí thành công!');
			}
		}

		$_lang = $this->session->userdata('_lang');
		$keyword = $this->input->get('keyword');
		$sort = $this->my_common->sort_orderby($this->input->get('sort_field'), $this->input->get('sort_value'));
		$config = $this->my_common->backend_pagination();
		$config['base_url'] = base_url().'backend/menu/index';
		$config['per_page'] = 10;
		
		/***
		* Load pagination when search
		***/
		if(!empty($keyword)){
			$config['total_rows'] = $this->db->from('menu')->like('title', $keyword)->where(array('lang' => $_lang))->count_all_results();
		}
		else{
			$config['total_rows'] = $this->db->from('menu')->where(array('lang' => $_lang))->count_all_results();
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
				$data['data']['_list'] = $this->db->from('menu')->like('title', $keyword)->where(array('lang' => $_lang))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
			}
			else{
				$data['data']['_list'] = $this->db->from('menu')->where(array('lang' => $_lang))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
			}
		}
		$data['data']['_config'] = $config;
		$data['data']['_page'] = $page;
		$data['data']['_sort'] = $sort;
		$data['data']['_keyword'] = $keyword;
		$data['seo']['title'] = "menu";
		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/menu/index", isset($data)?$data:NULL);
	}

	/*
    ******** Add new advertise
    **********************************************/
	public function add()
	{
		$this->my_auth->allow($this->auth, 'backend/menu/add');

		if($this->input->post('add')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
			if ($this->form_validation->run() == TRUE){
				$_post = $this->my_string->allow_post($_post, array('title', 'url', 'module', 'module_id'));
				$_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				$_post['lang'] = $this->session->userdata('_lang');
				$_post['userid_created'] = $this->auth['id'];
				$this->db->insert('menu', $_post); 
				$this->my_string->js_redirect('Thêm menu thành công!', base_url().'backend/menu/index');
			}
		}
		else{
			$_post['publish'] = 1;
			$data['data']['_post'] = $_post; 
		}
		$data['seo']['title'] = "Thêm menu";
		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/menu/add", isset($data)?$data:NULL);
	}

	/*
    ******** Edit advertise
    **********************************************/
	public function edit($id)
	{
		$this->my_auth->allow($this->auth, 'backend/menu/edit');

		$id = (int)$id;
		$continue = $this->input->get('continue');
		$adv = $this->db->where(array('id' => $id))->from('menu')->get()->row_array();
		if(!isset($adv) || count($adv) == 0)
			$this->my_string->php_redirect(base_url().'backend');
		if($adv['lang'] != $this->session->userdata('_lang'))
			$this->my_string->js_redirect('Ngôn ngữ không phù hợp!', !empty($continue)?base64_decode($continue):base_url().'backend/menu/index');

		if($this->input->post('edit')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
			if ($this->form_validation->run() == TRUE){
				$_post = $this->my_string->allow_post($_post, array('title', 'url', 'module', 'module_id'));
				$_post['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				$_post['userid_updated'] = $this->auth['id'];
				$this->db->where(array('id' => $id))->update('menu', $_post);
				$this->my_string->js_redirect('Sửa menu thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/menu/index');
			}
		}
		else{
			$data['data']['_post'] = $adv; 
		}
		$data['seo']['title'] = "Sửa menu";
		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/menu/edit", isset($data)?$data:NULL);
	}

	/*
    ******** Delete advertise
    **********************************************/
	public function del($id)
	{
		$this->my_auth->allow($this->auth, 'backend/menu/del');
		
		$id = (int)$id;
		$continue = $this->input->get('continue');
		$adv = $this->db->where(array('id' => $id))->from('menu')->get()->row_array();
		if(!isset($adv) || count($adv) == 0)
			$this->my_string->php_redirect(base_url().'backend');
		if($adv['lang'] != $this->session->userdata('_lang'))
			$this->my_string->js_redirect('Ngôn ngữ không phù hợp!', !empty($continue)?base64_decode($continue):base_url().'backend/menu/index');
		$this->db->delete('menu', array('id' => $id)); 
		$this->my_string->js_redirect('Xóa menu thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/menu/index');
	}

	/*
    ******** Set display status
    **********************************************/
	public function set($field, $id)
	{
		$this->my_auth->allow($this->auth, 'backend/menu/set');
		$id = (int)$id;
		$continue = $this->input->get('continue');
		$adv = $this->db->where(array('id' => $id))->from('menu')->get()->row_array();
		if(!isset($adv) || count($adv) == 0)
			$this->my_string->php_redirect(base_url().'backend/menu/index');
		if(!isset($adv[$field]))
			$this->my_string->php_redirect(base_url().'backend/menu/index');
		$this->db->where(array('id' => $id))->update('menu', array($field => (($adv[$field] == 1)?0:1)));
		$this->my_string->js_redirect('Thay đổi trạng thái thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/menu/index');
	}
}