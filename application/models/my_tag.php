<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_tag extends CI_Model{

	private $CI;
	private $auth;
	
	public function __construct(){
		$this->CI =& get_instance();
		$this->auth = $this->CI->my_auth->check(); // lấy data của account đã login vào
	}

	public function suggest($char = ''){
		$st = '<div class="title">';
		foreach (range('a', 'z') as $i) {
			if ($char == $i) {
				$st = $st . '<a href="<?php echo base_url(); ?>backend/tag/suggest/'.$i.'" title="Chủ đề với ký tự bắt đầu bằng: '.strtoupper($i).'"><strong>['.$i.']</strong></a>, ';
			}
			else{
				$st = $st . '<a href="<?php echo base_url(); ?>backend/tag/suggest/'.$i.'" title="Chủ đề với ký tự bắt đầu bằng: '.strtoupper($i).'">['.$i.']</a>, ';
			}
		}
		$st = $st . '</div><div class="suggest">';
		if ($char != '') {
			$data = $this->CI->db->select('title, alias')->like('title', $char, 'after')->from('tag')->order_by('title asc')->get()->result_array();
		}
		else{
			$data = $this->CI->db->select('title, alias')->from('tag')->order_by('title asc')->get()->result_array();
		}
		if (isset($data) && count($data)) {
			foreach ($data as $key => $val) {
				$st = $st . '<a href="#" title="'.htmlspecialchars($val['title']).'">['.htmlspecialchars($val['title']).']</a>';
			}
		}
		else{
			$st = $st . '<p>Không có từ chủ đề nào!</p>';
		}
		$st = $st . '</div>';
		echo $st;
	}
	public function insert($item = '', $list = ''){
		$flag = 0;
		$str = '';
		$temp = NULL;
		$list = explode(',', $list);
		if(isset($list) && count($list)){
			foreach ($list as $key => $val) {
				$val = trim($val);
				$val_alias = $this->CI->my_string->alias($val);
				if (empty($val)) {
					continue;
				}
				if ($temp !== NULL && in_array($val_alias, $temp) == TRUE) {
					continue;
				}
				$temp[] = $this->CI->my_string->alias($val);
				$str = $str.$val.', ';
				if ($val_alias == $this->CI->my_string->alias($item)) {
					$flag = 1;
				}
			}
		}
		if ($flag == 0) {
			$str = trim($str.$item.', ');
			$str = substr($str, 0, -1);
			die($str);
		}
		$str = trim($str);
		$str = substr($str, 0, -1);
		die($str);
	}

	public function insert_list($list = ''){
		$list = explode(',', $list);
		if (isset($list) && count($list)) {
			foreach ($list as $key => $val) {
				$val = trim($val);
				if (empty($val)) {
					continue;
				}
				$count = $this->CI->db->from('tag')->where(array('title' => $val))->count_all_results();
				if($count == 0){
					$post_data['publish'] = 1;
					$post_data['title'] = $val; 
					$post_data['alias'] = $this->CI->my_string->alias($val);
					$post_data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
					$post_data['userid_created'] = $this->auth['id'];
					$this->CI->db->insert('tag', $post_data);
				}
			}
		}
	}

	public function tags($data = ''){
		$tag = NULL;
		$data = explode(',', $data);
		if (isset($data) && count($data)) {
			foreach ($data as $key => $val) {
				$val = trim($val);
				if (empty($val)) {
					continue;
				}
				$tags[] = $val;
			}
		}
		return $tags;
	}
}