<?php
if ( ! function_exists('frontend_menu'))
{
	function frontend_menu($param)
	{
		$CI =& get_instance();
		$_lang = $CI->session->userdata('_lang');
		$menu = $CI->db->where(array('publish' => 1, 'lang' => $_lang))->from('menu')->order_by('order asc, id asc')->get()->result_array();
		$str = '';
		if (isset($menu) && count($menu)) {
			$str = '<ul class = "main">';
			foreach ($menu as $keyMain => $valMain) {
				if (!empty($val['title'])) {
					$str = $str.'<li class="main"><a href="'.$valMain['url'].'" title='.htmlspecialchars($valMain['title']).'">'.$valMain['title'].'</a></li>';
				}
				else if(!empty($valMain['module']) && $valMain['module_id'] > 0){
					if($valMain['module'] == 'article_category'){
						$str = $str.frontend_menu_article_category($param['article_category'], $valMain['module_id']);

					}
				}
			}
			$str = $str.'</ul>';
		}
		return $str;
	}
} 

if ( ! function_exists('frontend_menu_article_category'))
{
	function frontend_menu_article_category($data, $id = 0)
	{
		if (isset($data) && count($data)) {
			// Main
			$_itemMain = '';
			foreach ($data as $keyMain => $valMain) {
				if($valMain['id'] == $id){
					if (!empty($valMain['title'])) {
						$_itemMain = $_itemMain.'<li class="main"><a href="'.frontend_menu_link($valMain['route'], $valMain['alias'], $valMain['id'], '68').'" title="'.htmlspecialchars($valMain['title']).'" class="main" >'.$valMain['title'].'</a>';
						// Item
						$_tempItem = '';
						foreach ($data as $keyItem => $valItem) {
							if ($valMain['id'] == $valItem['parentid']) {
								$_tempItem = $_tempItem.'<li class="item"><a href="'.frontend_menu_link($valItem['route'], $valItem['alias'], $valItem['id'], '68').'" title="'.htmlspecialchars($valItem['title']).'" class="item" >'.$valItem['title'].'</a>';
								// Sub
								$_tempSub = '';
								foreach ($data as $keySub => $valSub) {
									if ($valItem['id'] == $valSub['parentid']) {
										$_tempSub = $_tempSub.'<li class="sub"><a href="'.frontend_menu_link($valSub['route'], $valSub['alias'], $valSub['id'], '68').'" title="'.htmlspecialchars($valSub['title']).'" class="sub" >'.$valSub['title'].'</a>';
										// Children
										$_tempChildren = '';
										foreach ($data as $keyChildren => $valChildren) {
											if ($valSub['id'] == $valChildren['parentid']) {
												$_tempChildren = $_tempChildren.'<li class="children"><a href="'.frontend_menu_link($valChildren['route'], $valChildren['alias'], $valChildren['id'], '68').'" title="'.htmlspecialchars($valChildren['title']).'" class="children" >'.$valChildren['title'].'</a>';
											}
											continue;
										}
										$_tempChildren = !empty($_tempChildren)?'<ul class="children">'.$_tempChildren.'</ul>':'';
										$_tempSub = $_tempSub.$_tempChildren.'</li>';
									}
									continue;
								}
								$_tempSub = !empty($_tempSub)?'<ul class="sub">'.$_tempSub.'</ul>':'';
								$_tempItem = $_tempItem.$_tempSub.'</li>';
							}
							continue;
						}

						$_tempItem = !empty($_tempItem)?'<ul class="item">'.$_tempItem.'</ul>':'';

						$_itemMain = $_itemMain.$_tempItem.'</li>';
					}
				}
				continue;
			}
			return $_itemMain;
		}
	}
} 

if ( ! function_exists('frontend_menu_getdata'))
{
	function frontend_menu_getdata($table = 'article_category')
	{
		$CI =& get_instance();
		$_lang = $CI->session->userdata('_lang');
		$menu = $CI->db->select('id, title, parentid, alias, route')->where(array('publish' => 1, 'lang' => $_lang))->from($table)->order_by('order asc, id asc')->get()->result_array();
		return $menu;
	}
} 

if ( ! function_exists('frontend_menu_link'))
{
	function frontend_menu_link($route, $alias, $id, $module)
	{
		if (!empty($route)) {
			return $route.HHV_URL_SUFFIX;
		}
		else
			return $alias.'-'.$module.$id.HHV_URL_SUFFIX;
	}
} 
