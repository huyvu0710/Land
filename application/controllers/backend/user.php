<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {

	private $auth;

	public function __construct()
    {
        parent::__construct();
        $this->my_layout->setLayout("layout/backend");
        $this->auth = $this->my_auth->check();
        if($this->auth == NULL) 
			$this->my_string->php_redirect(base_url().'backend');

		/***
		* Permission to access of members group
		***/
		$this->my_auth->allow($this->auth, 'backend/user');
    }

    /*
    ******** Members group
    **********************************************/
    public function group($page = 1)
	{
		$this->my_auth->allow($this->auth, 'backend/user/group');

		/***
		* Sort - follow input location
		***/
		if($this->input->post('sort')){
			$_order = $this->input->post('order');
			foreach ($_order as $keyOrder => $valOrder) {
                $_data[] = array(
                  'id' => $keyOrder ,
                  'order' => (int)$valOrder ,
                  'updated' => gmdate('Y-m-d H:i:s', time() + 7*3600 ),
                );
            }
            $this->db->update_batch('user_group', $_data, 'id'); 
            $this->my_string->js_reload('Sắp xếp thành công!');
		}

		$data['seo']['title'] = "Nhóm thành viên";
		$keyword = $this->input->get('keyword');
		$sort = $this->my_common->sort_orderby($this->input->get('sort_field'), $this->input->get('sort_value'));
		$config = $this->my_common->backend_pagination();
		$config['base_url'] = base_url().'backend/user/group';
		$config['per_page'] = 10;

		/***
		* Load pagination when search - follow 'title'
		***/
		if(!empty($keyword)){
			$config['total_rows'] = $this->db->from('user_group')->like('title', $keyword)->count_all_results();
		}
		else{
			$config['total_rows'] = $this->db->from('user_group')->count_all_results();
		}
		$config['uri_segment'] = 4; 
		$config['suffix'] = (isset($sort) && count($sort))?'?sort_field='.$sort['field'].'&sort_value='.$sort['value']:'';
		$config['suffix'] = $config['suffix'].(!empty($keyword)?'&keyword='.$keyword:'');
		$config['first_url'] = $config['base_url'].$config['suffix'];
		$this->pagination->initialize($config); 

		/***
		* Create pagination
		***/
		$data['data']['pagination'] = $this->pagination->create_links();

		/***
		* Load data when search - follow 'title'
		***/
		if(!empty($keyword)){
			$data['data']['_list'] = $this->db->from('user_group')->like('title', $keyword)->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
		}
		else{
			$data['data']['_list'] = $this->db->from('user_group')->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
		}

		$data['data']['_config'] = $config;
		$data['data']['_page'] = $page;
		$data['data']['_sort'] = $sort;
		$data['data']['_keyword'] = $keyword;
		$data['data']['auth'] = $this->auth;
		$this->my_layout->view("backend/user/group", isset($data)?$data:NULL);
	}

	/*
    ******** Add new members group
    **********************************************/
	public function addgroup()
	{
		$this->my_auth->allow($this->auth, 'backend/user/addgroup');

		$data['seo']['title'] = "Thêm nhóm thành viên";
		$data['data']['auth'] = $this->auth;

		if($this->input->post('add')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
			if ($this->form_validation->run() == TRUE){
				$_post = $this->my_string->allow_post($_post, array('title', 'allow', 'group'));
				$_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				$_post['userid_created'] = $this->auth['id'];
				$this->db->insert('user_group', $_post); 
				$this->my_string->js_redirect('Thêm nhóm thành viên thành công!', base_url().'backend/user/group');
			}
		}
		else{
			$_post['allow'] = 0;
			$data['data']['_post'] = $_post; 
		}
		$this->my_layout->view("backend/user/addgroup", isset($data)?$data:NULL);
	}

	/*
    ******** Edit members group
    **********************************************/
	public function editgroup($id)
	{
		$this->my_auth->allow($this->auth, 'backend/user/editgroup');

		$id = (int)$id;
		$group = $this->db->where(array('id' => $id))->from('user_group')->get()->row_array();
		if(!isset($group) || count($group) == 0)
			$this->my_string->php_redirect(base_url().'backend');

		$data['seo']['title'] = "Sửa đổi nhóm thành viên";
		$data['data']['auth'] = $this->auth;

		if($this->input->post('edit')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
			if ($this->form_validation->run() == TRUE){
				$_post = $this->my_string->allow_post($_post, array('title', 'allow', 'group'));
				$_post['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				$_post['userid_updated'] = $this->auth['id'];
				$this->db->where(array('id' => $id))->update('user_group', $_post);
				$this->my_string->js_redirect('Sửa nhóm thành viên thành công!', base_url().'backend/user/group');
			}
		}
		else{
			$data['data']['_post'] = $group; 
		}
		$this->my_layout->view("backend/user/editgroup", isset($data)?$data:NULL);
	}

	/*
    ******** Delete members group
    **********************************************/
	public function delgroup($id)
	{
		$this->my_auth->allow($this->auth, 'backend/user/delgroup');
		
		$id = (int)$id;
		$group = $this->db->where(array('id' => $id))->from('user_group')->get()->row_array();
		if(!isset($group) || count($group) == 0)
			$this->my_string->php_redirect(base_url().'backend');

		$count = $this->db->where(array('groupid' => $group['id']))->from('user')->count_all_results();
		if ($count > 0) {
			$this->my_string->js_redirect('Nhóm "'.$group['title'].'" vẫn còn thành viên!', base_url().'backend/user/group');
		}
		$this->db->delete('user_group', array('id' => $id)); 
		$this->my_string->js_redirect('Xóa nhóm thành viên thành công!', base_url().'backend/user/group');
	}

	/**********************************************
    ******** User
    **********************************************/

    public function index($page = 1)
    {
        $this->my_auth->allow($this->auth, 'backend/user/index');

        $keyword = $this->input->get('keyword');
        $groupid = (int)$this->input->get('groupid');
        $sort = $this->my_common->sort_orderby($this->input->get('sort_field'), $this->input->get('sort_value'));
        $config = $this->my_common->backend_pagination();
        $config['base_url'] = base_url().'backend/user/index';

        /***
        * Load pagination when search
        ***/
        if(!empty($keyword) && $groupid == 0){
            $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'user WHERE (`username` LIKE ? OR `email` LIKE ? OR `fullname` LIKE ?)';
            $_param = array('%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
            $config['total_rows'] = $this->db->query($_sql, $_param)->num_rows();
        }

        else if(empty($keyword) && $groupid > 0){
            $config['total_rows'] = $this->db->from('user')->where(array('groupid' => $groupid))->count_all_results();
        }
        else if(!empty($keyword) && $groupid > 0){
            $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'user WHERE `groupid` = ? AND (`username` LIKE ? OR `email` LIKE ? OR `fullname` LIKE ?)';
            $_param = array( $groupid, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
            $config['total_rows'] = $this->db->query($_sql, $_param)->num_rows();
        }
        else{
            $config['total_rows'] = $this->db->from('user')->count_all_results();
        }

        // Trang này ko có data. Load trang trước của nó
        $_totalpage = ceil($config['total_rows']/$config['per_page']);
        $page = ($page > $_totalpage)?$_totalpage:$page;

        $config['uri_segment'] = 4; 
        $config['suffix'] = (isset($sort) && count($sort))?'?sort_field='.$sort['field'].'&sort_value='.$sort['value']:'';
        $config['suffix'] = $config['suffix'].(($groupid > 0)?'&groupid'.$groupid:'');
        $config['suffix'] = $config['suffix'].(!empty($keyword)?'&keyword='.$keyword:'');
        $config['first_url'] = $config['base_url'].$config['suffix'];
        $config['per_page'] = 10;

        /***
        * Load data when search
        ***/
        if ($config['total_rows'] > 0) {

             $this->pagination->initialize($config); 

            /***
            * Create pagination
            ***/
            $data['data']['pagination'] = $this->pagination->create_links();

            if(!empty($keyword) && $groupid == 0){
                $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'user WHERE (`username` LIKE ? OR `email` LIKE ? OR `fullname` LIKE ?)';
                $_param = array('%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
                $data['data']['_list'] = $this->db->query($_sql, $_param)->result_array();
            }
            else if(empty($keyword) && $groupid > 0){
                $data['data']['_list'] = $this->db->from('user')->where(array('groupid' => $groupid))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
            }
            else if(!empty($keyword) && $groupid > 0){
                $_sql = 'SELECT * FROM `'.HHV_DB_PREFIX.'user` WHERE `groupid` = ? AND (`username` LIKE ? OR `email` LIKE ? OR `fullname` LIKE ?) ORDER BY `'.$sort['field'].'` '.$sort['value'].' LIMIT '.(($page-1) * $config['per_page']).', '.$config['per_page'];
                $_param = array($groupid, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
                $data['data']['_list'] = $this->db->query($_sql, $_param)->result_array();
            }
            else{
                $data['data']['_list'] = $this->db->from('user')->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
            }
        }
        
        $data['data']['_config'] = $config;
        $data['data']['_page'] = $page;
        $data['data']['_sort'] = $sort;
        $data['data']['_keyword'] = $keyword;
        $data['data']['_groupid'] = $groupid;
        $data['seo']['title'] = "Thành viên";
        $data['data']['auth'] = $this->auth;
        $_group = $this->db->select('id, title')->from('user_group')->get()->result_array();
        if (isset($_group) && count($_group)) {
        	$data['data']['_show']['groupid'][0] = '---';
        	foreach ($_group as $key => $val) {
        		$data['data']['_show']['groupid'][$val['id']] = $val['title'];
        	}
        }
        // $data['data']['_show']['groupid'] = $this->my_nestedset->dropdown('user_group', NULL, 'item');
        $this->my_layout->view("backend/user/index", isset($data)?$data:NULL);
    }

    /*
    ******** Add new user
    **********************************************/
	public function add()
	{
		$this->my_auth->allow($this->auth, 'backend/user/add');

		if($this->input->post('add')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[username]', 'Tên tài khoản', 'trim|required|min_length[3]|max_length[20]|regex_match[/^([a-z0-9_])+$/i]');
			$this->form_validation->set_rules('data[password]', 'Mật khẩu', 'trim|required');
			$this->form_validation->set_rules('data[repassword]', 'Xác nhận mật khẩu', 'trim|required||matches[data[password]]');
			$this->form_validation->set_rules('data[email]', 'Email', 'trim|required|valid_email|callback__email');
			$this->form_validation->set_rules('data[groupid]', 'Nhóm thành viên', 'trim|required|is_natural_no_zero');
			
			if ($this->form_validation->run() == TRUE){
				$_post = $this->my_string->allow_post($_post, array('username', 'password', 'email', 'groupid'));
				$_post['salt'] = $this->my_string->random(69, TRUE);
				$_post['password'] = $this->my_string->encode_password($_post['username'], $_post['password'], $_post['salt']);
				$_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				$this->db->insert('user', $_post); 
				$this->my_string->js_redirect('Thêm thành viên thành công!', base_url().'backend/user/index');
			}
		}
		else{
			$_post['publish'] = 1;
			$data['data']['_post'] = $_post; 
		}
		$data['seo']['title'] = "Thêm thành viên";
		$data['data']['auth'] = $this->auth;
		$_group = $this->db->select('id, title')->from('user_group')->get()->result_array();
        if (isset($_group) && count($_group)) {
        	$data['data']['_show']['groupid'][0] = '---';
        	foreach ($_group as $key => $val) {
        		$data['data']['_show']['groupid'][$val['id']] = $val['title'];
        	}
        }
		$this->my_layout->view("backend/user/add", isset($data)?$data:NULL);
	}

	/*
    ******** Edit user
    **********************************************/
	public function edit($id)
	{
		$this->my_auth->allow($this->auth, 'backend/user/edit');

		$id = (int)$id;
		$continue = $this->input->get('continue');
		$user = $this->db->where(array('id' => $id))->from('user')->get()->row_array();
		if(!isset($user) || count($user) == 0)
			$this->my_string->php_redirect(base_url().'backend');

		if($this->input->post('edit')){
			$_post = $this->input->post('data');
			$data['data']['_post'] = $_post; 

			$this->form_validation->set_error_delimiters('<li>', '</li>');
			$this->form_validation->set_rules('data[email]', 'Email', 'trim|required|valid_email|callback__email['.$user['email'].']');
			$this->form_validation->set_rules('data[groupid]', 'Nhóm thành viên', 'trim|required|is_natural_no_zero');
			if ($this->form_validation->run() == TRUE){
				$_post = $this->my_string->allow_post($_post, array('email', 'groupid'));
				$_post['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
				$this->db->where(array('id' => $id))->update('user', $_post);
				$this->my_string->js_redirect('Sửa thành viên thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/user/index');
			}
		}
		else{
			$data['data']['_post'] = $user; 
		}
		$data['seo']['title'] = "Sửa thành viên";
		$data['data']['_username'] = $user['username'];
		$data['data']['_email'] = $user['email'];
		$data['data']['auth'] = $this->auth;
		$_group = $this->db->select('id, title')->from('user_group')->get()->result_array();
        if (isset($_group) && count($_group)) {
        	$data['data']['_show']['groupid'][0] = '---';
        	foreach ($_group as $key => $val) {
        		$data['data']['_show']['groupid'][$val['id']] = $val['title'];
        	}
        }
		$this->my_layout->view("backend/user/edit", isset($data)?$data:NULL);
	}

	public function _email($email, $old_email){
        if (isset($old_email) && !empty($old_email)) {
            $count = $this->db->where(array('email' => $email, 'email !=' => $old_email))->from('user')->count_all_results();
        }
        else {
           $count = $this->db->where(array('email' => $email))->from('user')->count_all_results();
        }
        if($count > 0){
            $this->form_validation->set_message('_email', 'Email '.$email.' đã tồn tại.');
            return FALSE;
        }
        return TRUE;
	}

	/*
    ******** Delete user
    **********************************************/
    public function del($id)
    {
        $this->my_auth->allow($this->auth, 'backend/user/del');
        $id = (int)$id;
        $user = $this->db->where(array('id' => $id))->from('user')->get()->row_array();
        if(!isset($user) || count($user) == 0)
            $this->my_string->php_redirect(base_url().'backend/user/index');
        if ($id == $this->auth['id']) {
            $this->my_string->js_redirect('Không thể tự xóa mình!', base_url().'backend/user/index');
        }
        $count = $this->db->where(array('userid_created' => $id))->from('article_item')->count_all_results();
        if ($count > 0) {
            $this->my_string->js_redirect('Thành viên vẫn còn bài viết!', base_url().'backend/user/index');
        }
        $this->db->delete('user', array('id' => $id)); 
        $this->my_string->js_redirect('Xóa thành viên thành công!', base_url().'backend/user/index');
    }
}