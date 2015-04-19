<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_nestedset{
	private $CI;
	private $auth;

	public function __construct(){
		$this->CI =& get_instance();
		$this->auth = $this->CI->my_auth->check(); // lấy data của account đã login vào
	}

	// Tự động tạo node gốc nếu là bảng trắng
	public function check_empty($table = ''){
		$count = $this->CI->db->from($table)->count_all_results();
		if ($count == 0) {
			$post_data['title'] = 'Root';
			$post_data['lang'] = 'all';
			$post_data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
			$post_data['userid_created'] = $this->auth['id'];
			$this->CI->db->insert($table, $post_data);
			$this->CI->my_string->php_redirect(HHV_BASE_URL.'backend/'.current(explode('_', $table)).'/add'.end(explode('_', $table)));
		}
	}

	// Mảng lựa chọn sử dụng cho danh mục đổ xuống
	public function dropdown($table = '', $param = NULL, $type = 'category'){
		$temp = NULL;
		$_lang = $this->CI->session->userdata('_lang');
		if ($param == NULL) {
			$data = $this->CI->db->select('id, title, level')->from($table)->where(array('lang' => $_lang))->or_where(array('lang' => 'all'))->order_by('lft asc')->get()->result_array();
		}
		else{
			$data = $this->CI->db->select('id, title, level')->from($table)->where(array('lang' => $_lang))->or_where(array('lang' => 'all'))->where($param)->order_by('lft asc')->get()->result_array();
		}
		if (isset($data) && count($data)) {
			if ($type == 'category') {
				foreach ($data as $key => $val) {
					$temp[$val['id']] = str_repeat('|----- ', $val['level']).$val['title'];
				}
			}
			else if ($type == 'item') {
				foreach ($data as $key => $val) {
					if ($val['level'] == 0) {
						$temp['0'] = $val['title'];
					}
					else{
						$temp[$val['id']] = str_repeat('|----- ', $val['level']).$val['title'];
					}
				}
			}
		}
		return $temp;
	}

	// Mảng dữ liệu để hiển thị danh sách
	public function data($table = '', $param = NULL){
		$_lang = $this->CI->session->userdata('_lang');
		if ($param == NULL) {
			$data = $this->CI->db->from($table)->where(array('lang' => $_lang))->or_where(array('lang' => 'all'))->order_by('lft asc')->get()->result_array();
		}
		else{
			$data = $this->CI->db->from($table)->where(array('lang' => $_lang))->or_where(array('lang' => 'all'))->where($param)->order_by('lft asc')->get()->result_array();
		}
		return $data;
	}

	// Mảng dữ liệu
	public function arr($table = ''){
		$_lang = $this->CI->session->userdata('_lang');
		return $this->CI->db->select('id, title, parentid, level, order')->from($table)->where(array('lang' => $_lang))->or_where(array('lang' => 'all'))->order_by('order asc, id asc')->get()->result_array();
	}

	// Chi tiết
	public function get($table = '', $param = NULL){
		return $this->CI->db->select('id, title, parentid, level, order')->from($table)->where($param)->get()->row_array();
	}

	// Đệ quy
	public function recursive($id = 0, $arr = NULL, $tree = NULL){
		foreach ($arr as $val) {
			if ($val['parentid'] == $id) {
				$tree[] = $val;
				$tree = $this->recursive($val['id'], $arr, $tree);
			}
		}
		return $tree;
	}

	// set level
	public function level($table = ''){
		$data = $this->CI->my_nestedset->recursive(0, $this->CI->my_nestedset->arr($table));
		if (isset($data) && count($data)) {
			// Duyệt tuần tự từ trên xuống theo mảng đệ quy
			foreach ($data as $key => $val) {
				// Nếu là node Root
				if ($val['parentid'] == 0) {
					$level = 0;
				}
				// Nếu không phải node Root thì lấy level của cấp cha đó + thêm 1
				else if($val['parentid'] > 0){
					$parent = $this->CI->my_nestedset->get($table, array('id' => $val['parentid']));
					$level = ($parent['level'] + 1);
				}
				$this->CI->db->where('id', $val['id'])->update($table, array('level' => $level));
			}
		}
	}

	// Tạo left - right
	public function lftrgt($table = ''){
		$data = $this->CI->my_nestedset->recursive(0, $this->CI->my_nestedset->arr($table));
		if (isset($data) && count($data)) {
			$i = 0;
			$max = NULL;
			$flag = 0;
			foreach ($data as $key => $val) {
				// Tổng số node con của node
				$countSubItem = count($this->recursive($val['id'], $data));
				// Các node đầu tiên trong level
				if (!isset($max[$val['level']])) {
					$left = $i;
					$right = ($countSubItem * 2) + 1 + $i;
					$max[$val['level']] = $right;
					if ($left + 1 == $right) {
						$flag = 1; 
					}
					else{
						$i++;
					}
				}
				else{
					// Các node được duyệt ngay sau node lá
					if($flag == 1){
						$flag = 0;
						$i = $max[$val['level']] + 1;
						$left = $i;
						$right = ($countSubItem * 2) + 1 + $i;
						$max[$val['level']] = $right;
						if ($left + 1 == $right) {
							$flag = 1;
						}
						else{
							$i++;
						}
					}
					else{
						$left = $i;
						$right = ($countSubItem * 2) + 1 + $i;
						$max[$val['level']] = $right;
						if ($left + 1 == $right) {
							$flag = 1;
						}
						else{
							$i++;
						}
					}
				}
				$this->CI->db->where('id', $val['id'])->update($table, array('lft' => $left, 'rgt' => $right));
			}
		}
	}

	public function set($table = ''){
		$this->CI->my_nestedset->level($table);
		$this->CI->my_nestedset->lftrgt($table);
	}

	public function check_parentid($table = '', $parentid = 0, $catid = 0){
		$_lang = $this->CI->session->userdata('_lang');
		if ($parentid == $catid) {
			$this->CI->form_validation->set_message('_parentid', 'Không thể chọn chính nó làm danh mục cha.');
			return FALSE;
		}
		$data = $this->CI->db->select('lft, rgt')->from($table)->where(array('lang' => $_lang))->where(array('id' => $catid))->get()->row_array();
		if (isset($data) && count($data)) {
			$children = $this->CI->db->select('id')->from($table)->where(array('lang' => $_lang))->where(array('lft >' => $data['lft'], 'lft <' => $data['rgt']))->get()->row_array();
			if (isset($children) && count($children)) {
				foreach ($children as $key => $val) {
					if ($parentid == $val) {
						$this->CI->form_validation->set_message('_parentid', 'Không thể chọn danh mục con làm danh mục cha.');
						return FALSE;
					}
				}
			}
		}
		else{
			$this->CI->form_validation->set_message('_parentid', 'Danh mục cha không tồn tại.');
			return FALSE;
		}
		return TRUE;
	}

	// Danh sách node con
	public function children($table = '', $param = NULL){
		$_lang = $this->CI->session->userdata('_lang');
		$temp = NULL;
		$children = $this->CI->db->select('id')->from($table)->where(array('lang' => $_lang))->where($param)->get()->result_array();
		if (isset($children) && count($children)) {
			foreach ($children as $key => $val) {
				$temp[] = $val['id'];
			}
		}
		return $temp;
	}
}