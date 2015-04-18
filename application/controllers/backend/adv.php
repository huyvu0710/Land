<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adv extends MY_Controller 
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
		$this->my_auth->allow($this->auth, 'backend/adv');
    }

    /*
    ******** Advertise
    **********************************************/
    public function index($page = 1)
	{
		$this->my_auth->allow($this->auth, 'backend/adv/index');

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
	            $this->db->update_batch('adv', $_data, 'id'); 
	            $this->my_string->js_reload('Cập nhật vị trí thành công!');
			}
		}

		$_lang = $this->session->userdata('_lang');
		$keyword = $this->input->get('keyword');
		$sort = $this->my_common->sort_orderby($this->input->get('sort_field'), $this->input->get('sort_value'));
		$config = $this->my_common->backend_pagination();
		$config['base_url'] = base_url().'backend/adv/index';
		$config['per_page'] = 10;
		
		/***
		* Load pagination when search
		***/
		if(!empty($keyword)){
			$config['total_rows'] = $this->db->from('adv')->like('title', $keyword)->where(array('lang' => $_lang))->count_all_results();
		}
		else{
			$config['total_rows'] = $this->db->from('adv')->where(array('lang' => $_lang))->count_all_results();
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
				$data['data']['_list'] = $this->db->from('adv')->like('title', $keyword)->where(array('lang' => $_lang))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
			}
			else{
				$data['data']['_list'] = $this->db->from('adv')->where(array('lang' => $_lang))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
			}
		}
		$data['data']['_config'] = $config;
		$data['data']['_page'] = $page;
		$data['data']['_sort'] = $sort;
		$data['data']['_keyword'] = $keyword;
		$data['seo']['title'] = "Quảng cáo";
		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/adv/index", isset($data)?$data:NULL);
	}

	/*
    ******** Add new advertise
    **********************************************/
	public function add()
	{
		$this->my_auth->allow($this->auth, 'backend/adv/add');

		if($this->input->post('add')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[time_start]', 'Ngày bắt đầu', 'trim|required|callback__date['.json_encode(array('time_start' => $_post['time_start'], 'time_end' => $_post['time_end'])).']');
			$this->form_validation->set_rules('data[time_end]', 'Ngày kết thúc', 'trim|required');
			$this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
			$this->form_validation->set_rules('data[content]', 'Nội dung', 'trim|required');
			$this->form_validation->set_rules('data[position]', 'Vị trí', 'trim|required');
			if ($this->form_validation->run() == TRUE){
				$_post = $this->my_string->allow_post($_post, array('title', 'content', 'position', 'time_start', 'time_end', 'publish'));
				$_post['time_start'] = gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-', $_post['time_start'])) + 7*3600 );
				$_post['time_end'] = gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-',$_post['time_end'])) + 7*3600 );
				$_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				$_post['userid_created'] = $this->auth['id'];
				$_post['lang'] = $this->session->userdata('_lang');
				$this->db->insert('adv', $_post); 
				$this->my_string->js_redirect('Thêm quảng cáo thành công!', base_url().'backend/adv/index');
			}
		}
		else{
			$_post['publish'] = 1;
			$data['data']['_post'] = $_post; 
		}
		$data['seo']['title'] = "Thêm quảng cáo";
		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/adv/add", isset($data)?$data:NULL);
	}

	/*
    ******** Edit advertise
    **********************************************/
	public function edit($id)
	{
		$this->my_auth->allow($this->auth, 'backend/adv/edit');

		$id = (int)$id;
		$continue = $this->input->get('continue');
		$adv = $this->db->where(array('id' => $id))->from('adv')->get()->row_array();
		if(!isset($adv) || count($adv) == 0)
			$this->my_string->php_redirect(base_url().'backend');
		if($adv['lang'] != $this->session->userdata('_lang'))
			$this->my_string->js_redirect('Ngôn ngữ không phù hợp!', !empty($continue)?base64_decode($continue):base_url().'backend/adv/index');

		if($this->input->post('edit')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[time_start]', 'Ngày bắt đầu', 'trim|required|callback__date['.json_encode(array('time_start' => $_post['time_start'], 'time_end' => $_post['time_end'])).']');
			$this->form_validation->set_rules('data[time_end]', 'Ngày kết thúc', 'trim|required');
			$this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
			$this->form_validation->set_rules('data[content]', 'Nội dung', 'trim|required');
			$this->form_validation->set_rules('data[position]', 'Vị trí', 'trim|required');
			if ($this->form_validation->run() == TRUE){
				$_post = $this->my_string->allow_post($_post, array('title', 'content', 'position', 'time_start', 'time_end', 'publish'));
				$_post['time_start'] = gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-', $_post['time_start'])) + 7*3600 );
				$_post['time_end'] = gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-',$_post['time_end'])) + 7*3600 );
				$_post['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				$_post['userid_updated'] = $this->auth['id'];
				$this->db->where(array('id' => $id))->update('adv', $_post);
				$this->my_string->js_redirect('Sửa quảng cáo thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/adv/index');
			}
		}
		else{
			$adv['time_start'] = ($adv['time_start'] != '1970-01-01 07:00:00')?gmdate('H:i:s d/m/Y', strtotime($adv['time_start']) + 7*3600 ):'';
			$adv['time_end'] = ($adv['time_end'] != '1970-01-01 07:00:00')?gmdate('H:i:s d/m/Y', strtotime($adv['time_end']) + 7*3600 ):'';
			$data['data']['_post'] = $adv; 
		}
		$data['seo']['title'] = "Sửa quảng cáo";
		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/adv/edit", isset($data)?$data:NULL);
	}

	/*
    ******** Delete advertise
    **********************************************/
	public function del($id)
	{
		$this->my_auth->allow($this->auth, 'backend/adv/del');
		
		$id = (int)$id;
		$continue = $this->input->get('continue');
		$adv = $this->db->where(array('id' => $id))->from('adv')->get()->row_array();
		if(!isset($adv) || count($adv) == 0)
			$this->my_string->php_redirect(base_url().'backend');
		if($adv['lang'] != $this->session->userdata('_lang'))
			$this->my_string->js_redirect('Ngôn ngữ không phù hợp!', !empty($continue)?base64_decode($continue):base_url().'backend/adv/index');
		$this->db->delete('adv', array('id' => $id)); 
		$this->my_string->js_redirect('Xóa quảng cáo thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/adv/index');
	}

	/***
	* Set validate to date
	***/
	public function _date($title, $date){
		$date = json_decode($date, TRUE);
		if (isset($date['time_start']) && !empty($date['time_start']) && isset($date['time_end']) && !empty($date['time_end']) && (strtotime(str_replace('/', '-', $date['time_start'])) + 7*3600 ) >= (strtotime(str_replace('/', '-',$date['time_end'])) + 7*3600 )) {
			$this->form_validation->set_message('_date', 'Ngày kết thúc phải lớn hơn ngày bắt đầu!');
			return FALSE;
		}
		return TRUE;
	}

	/*
    ******** Set display status
    **********************************************/
	public function set($field, $id)
	{
		$this->my_auth->allow($this->auth, 'backend/adv/set');
		$id = (int)$id;
		$continue = $this->input->get('continue');
		$adv = $this->db->where(array('id' => $id))->from('adv')->get()->row_array();
		if(!isset($adv) || count($adv) == 0)
			$this->my_string->php_redirect(base_url().'backend/adv/index');
		if(!isset($adv[$field]))
			$this->my_string->php_redirect(base_url().'backend/adv/index');
		$this->db->where(array('id' => $id))->update('adv', array($field => (($adv[$field] == 1)?0:1)));
		$this->my_string->js_redirect('Thay đổi trạng thái thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/adv/index');
	}
}