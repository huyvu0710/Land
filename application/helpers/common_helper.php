<?php
if ( ! function_exists('common_valuepost'))
{
	function common_valuepost($value)
	{
		return !empty($value) ? htmlspecialchars($value) : '';
	}
} 

if ( ! function_exists('common_showerror'))
{
	function common_showerror($error)
	{
		return (isset($error) && !empty($error)) ? '<ul class="error">'.$error.'</ul>' : '';
	}
}

if ( ! function_exists('common_fullurl'))
{
	function common_fullurl()
	{
		return ('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']).((!empty($_SERVER['QUERY_STRING']))?('?'.$_SERVER['QUERY_STRING']):'');
	}
}

if ( ! function_exists('get_user'))
{
	function get_user($id, $param)
	{
		$CI =& get_instance();
		$user = $CI->db->select($param)->where(array('id' => $id))->from('user')->get()->row_array();
		if(isset($user) && count($user)){
			return $user;
		}
		else{
			return NULL;
		}
	}
}

if ( ! function_exists('get_category'))
{
	function get_category($table, $select, $param)
	{
		$CI =& get_instance();
		$category = $CI->db->select($select)->where($param)->from($table)->get()->row_array();
		if(isset($category) && count($category)){
			return $category;
		}
		else{
			return NULL;
		}
	}
}

if ( ! function_exists('get_count_post'))
{
	function get_count_post($table, $param)
	{
		$CI =& get_instance();
		$count = $CI->db->where($param)->from($table)->count_all_results(); 
		return $count;
	}
}

if ( ! function_exists('get_count_item'))
{
	function get_count_item($table, $param)
	{
		$CI =& get_instance();
		$count = $CI->db->where($param)->from($table)->count_all_results(); 
		return $count;
	}
}

if ( ! function_exists('get_count_user_group'))
{
	function get_count_user_group($param)
	{
		$CI =& get_instance();
		$count = $CI->db->where($param)->from('user')->count_all_results();
		return $count;
	}
}

if ( ! function_exists('get_link_sort'))
{
	function get_link_sort($param)
	{
		$str = '';
		$flag = 0;
		if (isset($param) && count($param)) {
			if ($param['field'] == $param['sort_field']) {
				$param['sort_value'] = ($param['sort_value'] == 'asc')?'desc':'asc';
				$flag = 1;
			}
			else{
				$param['sort_field'] = $param['field'];
				$param['sort_value'] = 'asc';
			}
			foreach ($param as $key => $val) {
				if ($key == 'base_url') {
					$str = $val;
				}
				else if ($key == 'page') {
					$str = $str.(($val > 1)?('/'.$val):'').'?';
				}
				else if (in_array($key, array('field', 'title'))) {
					continue;
				}
				else{
					$str = $str.$key.'='.$val.'&';
				}
			}
		}
		return '<a href="'.substr($str, 0, -1).'">'.$param['title'].(($flag == 1)?'<img src="'.HHV_BASE_URL.'public/template/backend/images/'.$param['sort_value'].'.png" title="'.$param['sort_field'].' '.$param['sort_value'].'" />':'').'</a>';
	}
}

if ( ! function_exists('get_lang'))
{
	function get_lang($lang)
	{
		$CI =& get_instance();
		if(empty($lang)){
            $_lang = $CI->session->userdata('_lang');
            if (isset($_lang) && !empty($_lang) && $_lang != 'vi') {
	                $CI->session->set_userdata('_lang', 'vi');
	            }
	        }
        else{
            if (!empty($lang) && in_array($lang, array('jp', 'en'))) {
	                $CI->session->set_userdata('_lang', $lang);
            	}
        	}
        $_lang = $CI->session->userdata('_lang');
        $CI->lang->load('frontend', $_lang); // $this->lang->load('filename', 'language'); filename: frontend_lang
    }
}