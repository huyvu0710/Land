<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_string extends CI_Model{
	private $CI;
	public function __construct(){
		$this->CI =& get_instance();
	}

	public function random($leng = 10, $char = FALSE){
		if($char = FALSE) $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
		else $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		mt_srand((double)microtime() * 1000000);
		$salt = '';
		for ($i=0; $i < $leng; $i++) { 
			$salt = $salt . substr($s, (mt_rand()%(strlen($s))), 1);
		}
		return $salt;
	}

	public function encode_password($username, $password = '', $salt = ''){
		return md5($salt.$username.md5($salt.$username.md5($password).$username.$salt).$salt);
	}

	public function encode_cookie($cookie){
		return $this->random(10).base64_encode($cookie);
	}

	public function decode_cookie($cookie){
		return base64_decode(substr($cookie, 10));
	}

	public function encode_folder($cookie){
		return $this->random(10).base64_encode($cookie);
	}

	public function decode_folder($cookie){
		return base64_decode(substr($cookie, 10));
	}
	
	public function allow_post($param, $allow){
		$_temp = NULL;
		if(isset($param) && count($param) && isset($allow) && count($allow)){
			foreach ($param as $key => $val) {
				if (in_array($key, $allow) ==TRUE) {
					$_temp[$key] = trim($val);
				}
			}
			return $_temp;
		}
		return $param;
	}

	public function php_redirect($url){
		header('Location: '.$url); 
		die;
	}

	public function js_redirect($alert, $url){
		die('<meta charset="UTF-8"><script type="text/javascript">alert(\'' .$alert. '\'); location.href = \'' .$url. '\';</script>');
	}

	public function js_reload($alert){
		die('<meta charset="UTF-8"><script type="text/javascript">alert(\'' .$alert. '\'); location.reload();</script>');
	}

	public function trim_array($arr){
		if (isset($arr) && count($arr)) {
			$_arr = NULL;
			foreach ($arr as $key => $val) {
				$val = trim($val);
				if (empty($val)) {
					continue;
				}
				$_arr[] = $val;
			}
		}
		return $_arr;
	}

	public function removeutf8($str = ''){
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);

		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		
		$str = preg_replace("/( )/", '-', $str);
		return $str;
	}

	public function alias($str = NULL){
		$str = $this->removeutf8($str);
		$str = preg_replace('/[^a-zA-Z0-9-]/i', '', $str);
		$str = str_replace(array(
			'------------------',
			'-----------------',
			'----------------',
			'---------------',
			'--------------',
			'-------------',
			'------------',
			'-----------',
			'----------',	
			'---------',
			'--------',
			'-------',
			'------',
			'-----',
			'----',
			'---',
			'--',
			),
			'-',
			$str
		);
		$str = str_replace(array(
			'------------------',
			'-----------------',
			'----------------',
			'---------------',
			'--------------',
			'-------------',
			'------------',
			'-----------',
			'----------',	
			'---------',
			'--------',
			'-------',
			'------',
			'-----',
			'----',
			'---',
			'--',
			),
			'-',
			$str
		);
		if(!empty($str)){
			if ($str[strlen($str)-1] == '-') {
				$str = substr($str, 0, -1);
			}
			if ($str[0] == '-') {
				$str = substr($str, 1);
			}
		}
		return strtolower($str);
	}
}