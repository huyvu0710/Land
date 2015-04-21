<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Article extends MY_Controller 
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
        $this->my_auth->allow($this->auth, 'backend/article');
    }

    /**********************************************
    ******** Article category
    **********************************************/
    public function category(){
        $this->my_auth->allow($this->auth, 'backend/article/category');

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
                $this->db->update_batch('article_category', $_data, 'id'); 
                $this->my_string->js_reload('Cập nhật vị trí thành công!');
            }
        }

        $this->my_nestedset->set('article_category');
        $data['data']['_list'] = $this->my_nestedset->data('article_category');
        $data['seo']['title'] = 'Danh mục bài viết'; 
        $data['data']['auth'] = $this->auth;
        $this->my_layout->view('backend/article/category', isset($data)?$data:NULL);
    }

    /*
    ******** Add new categories of articles
    **********************************************/
    public function addcategory()
    {
        $this->my_auth->allow($this->auth, 'backend/article/addcategory');
        $this->my_nestedset->check_empty('article_category');
        if($this->input->post('add')){
            $_post = $this->input->post('data');
            $data['data']['_post'] = $_post; 

            $this->form_validation->set_error_delimiters('<li>', '</li>');
            $this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
            $this->form_validation->set_rules('data[parentid]', 'Node cha', 'trim|required|is_natural_no_zero');
            if (isset($_post['route']) && !empty($_post['route'])) {
                $this->form_validation->set_rules('data[route]', 'Url tùy biến', 'trim|required|callback__route');
                $_post['route'] = $this->my_string->alias($_post['route']);
            } 
            if ($this->form_validation->run() == TRUE){
                $_post = $this->my_string->allow_post($_post, array('title', 'parentid', 'route', 'description', 'publish', 'meta_title', 'meta_keywords', 'meta_description'));
                $_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                $_post['userid_created'] = $this->auth['id'];
                $_post['lang'] = $this->session->userdata('_lang');
                $_post['alias'] = $this->my_string->alias($_post['title']);
                $this->db->insert('article_category', $_post); 
                if (isset($_post['route']) && !empty($_post['route'])) {
                    $this->my_route->insert(array(
                        'url' => $_post['route'],
                        'param' => 'article/category/'.$this->db->insert_id(),
                        'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
                    ));
                }
                $this->my_string->js_redirect('Thêm danh mục thành công!', base_url().'backend/article/category');
            }
        }
        else{
            $_post['publish'] = 1;
            $data['data']['_post'] = $_post; 
        }
        $data['data']['_show']['parentid'] = $this->my_nestedset->dropdown('article_category', NULL, 'category');
        $data['seo']['title'] = "Thêm danh mục";
        $data['data']['auth'] = $this->auth;  
        $this->my_layout->view("backend/article/addcategory", isset($data)?$data:NULL);
    }

    /*
    ******** Edit categories of articles
    **********************************************/
    public function editcategory($id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/editcategory');

        $id = (int)$id;
        $category = $this->db->where(array('id' => $id))->from('article_category')->get()->row_array();
        if(!isset($category) || count($category) == 0)
            $this->my_string->php_redirect(base_url().'backend/article/category');
        if ($category['level'] == 0) {
            $this->my_string->js_redirect('Không thể sửa Root!', base_url().'backend/article/category');
        }
        if($category['lang'] != $this->session->userdata('_lang'))
            $this->my_string->js_redirect('Ngôn ngữ không phù hợp!', base_url().'backend/article/category');

        if($this->input->post('edit')){
            $_post = $this->input->post('data');
            $data['data']['_post'] = $_post; 

            $this->form_validation->set_error_delimiters('<li>', '</li>');
            $this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
            $this->form_validation->set_rules('data[parentid]', 'Node cha', 'trim|required|is_natural_no_zero|callback__parentid[' .$id. ']');
            if (isset($_post['route']) && !empty($_post['route'])) {
               $this->form_validation->set_rules('data[route]', 'Url tùy biến', 'trim|required|callback__route['.$category['route'].']');
                $_post['route'] = $this->my_string->alias($_post['route']);
            } 
            if ($this->form_validation->run() == TRUE){
                $_post = $this->my_string->allow_post($_post, array('title', 'parentid', 'route', 'description', 'publish', 'meta_title', 'meta_keywords', 'meta_description'));
                $_post['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                $_post['userid_updated'] = $this->auth['id'];
                $_post['alias'] = $this->my_string->alias($_post['title']);
                $this->db->where(array('id' => $id))->update('article_category', $_post);
                if (isset($_post['route']) && !empty($_post['route'])) {
                    $this->my_route->update('article/category/'.$id,$_post['route']);
                }
                $this->my_string->js_redirect('Sửa danh mục thành công!', base_url().'backend/article/category');
            }
        }
        else{
            $data['data']['_post'] = $category; 
        }
        $data['data']['_show']['parentid'] = $this->my_nestedset->dropdown('article_category', NULL, 'category');
        $data['seo']['title'] = "Sửa danh mục";
        $data['data']['auth'] = $this->auth;
        $this->my_layout->view("backend/article/editcategory", isset($data)?$data:NULL);
    }

    /***
    * Set validate to parentid
    ***/
    public function _parentid($parentid, $catid){
        $parentid = (int)$parentid;
        $catid = (int)$catid;
        return $this->my_nestedset->check_parentid('article_category', $parentid, $catid);
    }

    /*
    ******** Delete categories of articles
    **********************************************/
    public function delcategory($id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/delcategory');
        
        $id = (int)$id;
        $category = $this->db->where(array('id' => $id))->from('article_category')->get()->row_array();
        if(!isset($category) || count($category) == 0)
            $this->my_string->php_redirect(base_url().'backend');
        if ($category['level'] == 0){
            $this->my_string->js_redirect('Không thể xóa Root!', base_url().'backend/article/category');
        }
        if($category['lang'] != $this->session->userdata('_lang'))
            $this->my_string->js_redirect('Ngôn ngữ không phù hợp!', base_url().'backend/article/category');
        $count = $this->my_nestedset->children('article_category', array(
                'lft >' => $category['lft'],
                'rgt <' => $category['rgt']
            ));
        if ($count > 0) {
            $this->my_string->js_redirect('Vẫn còn chuyên mục con!', base_url().'backend/article/category');
        }
        $count = $this->db->from('article_item')->where(array('parentid' => $id))->count_all_results();
        if ($count > 0) {
            $this->my_string->js_redirect('Vẫn còn bài viết!', base_url().'backend/article/category');
        }
        $this->db->delete('article_category', array('id' => $id)); 
        $this->my_string->js_redirect('Xóa danh mục thành công!', base_url().'backend/article/category');
    }

    /*
    ******** Set display status
    **********************************************/
    public function setcategory($field, $id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/setcategory');
        $id = (int)$id;
        $category = $this->db->where(array('id' => $id))->from('article_category')->get()->row_array();
        if(!isset($category) || count($category) == 0)
            $this->my_string->php_redirect(base_url().'backend/article/category');
        if(!isset($category[$field]))
            $this->my_string->php_redirect(base_url().'backend/article/category');
        $this->db->where(array('id' => $id))->update('article_category', array($field => (($category[$field] == 1)?0:1)));
        $this->my_string->js_redirect('Thay đổi trạng thái thành công!', base_url().'backend/article/category');
    }

    /**********************************************
    ******** Article News
    **********************************************/

    public function item($page = 1)
    {
        $this->my_auth->allow($this->auth, 'backend/article/item');

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

                $this->db->update_batch('article_item', $_data, 'id'); 
                $this->my_string->js_reload('Cập nhật vị trí thành công!');
            }
        }

        /***
        * Choose delete multiple
        ***/
        if($this->input->post('del')){
            $_checkbox = $this->input->post('checkbox');
            if (isset($_checkbox) && count($_checkbox) >= 2) {
                $_temp = NULL;
                foreach ($_checkbox as $keyCheckbox => $valCheckbox) {
                    $_temp[] = $keyCheckbox;
                }
                if (count($_temp)) {
                    $this->db->where_in('id', $_temp)->delete('article_item');
                    $this->my_string->js_reload('Xóa lựa chọn thành công!');
                }
            }
            else
                $this->my_string->js_reload('Chọn nhiều hơn 2 đối tượng để xóa!');
        }
        $_lang = $this->session->userdata('_lang');
        $keyword = $this->input->get('keyword');
        $parentid = (int)$this->input->get('parentid');
        $sort = $this->my_common->sort_orderby($this->input->get('sort_field'), $this->input->get('sort_value'));
        $config = $this->my_common->backend_pagination();
        $config['base_url'] = base_url().'backend/article/item';
        $config['per_page'] = 5;

        /***
        * Load pagination when search
        ***/
        if(!empty($keyword) && $parentid == 0){
            $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'article_item WHERE `lang` = ? AND (`title` LIKE ? OR `description` LIKE ? OR `content` LIKE ?)';
            $_param = array($_lang, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
            $config['total_rows'] = $this->db->query($_sql, $_param)->num_rows();
        }

        else if(empty($keyword) && $parentid > 0){
            $config['total_rows'] = $this->db->from('article_item')->where(array('lang' => $_lang))->where(array('parentid' => $parentid))->count_all_results();
        }
        else if(!empty($keyword) && $parentid > 0){
            $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'article_item WHERE `lang` = ? AND `parentid` = ? AND (`title` LIKE ? OR `description` LIKE ? OR `content` LIKE ?)';
            $_param = array($_lang, $parentid, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
            $config['total_rows'] = $this->db->query($_sql, $_param)->num_rows();
        }
        else{
            $config['total_rows'] = $this->db->from('article_item')->where(array('lang' => $_lang))->count_all_results();
        }

        // Trang này ko có data. Load trang trước của nó
        $_totalpage = ceil($config['total_rows']/$config['per_page']);
        $page = ($page > $_totalpage)?$_totalpage:$page;

        $config['uri_segment'] = 4; 
        $config['suffix'] = (isset($sort) && count($sort))?'?sort_field='.$sort['field'].'&sort_value='.$sort['value']:'';
        $config['suffix'] = $config['suffix'].(($parentid > 0)?'&parentid'.$parentid:'');
        $config['suffix'] = $config['suffix'].(!empty($keyword)?'&keyword='.$keyword:'');
        $config['first_url'] = $config['base_url'].$config['suffix'];

        /***
        * Load data when search
        ***/
        if ($config['total_rows'] > 0) {

             $this->pagination->initialize($config); 

            /***
            * Create pagination
            ***/
            $data['data']['pagination'] = $this->pagination->create_links();

            if(!empty($keyword) && $parentid == 0){
                $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'article_item WHERE `lang` = ? AND (`title` LIKE ? OR `description` LIKE ? OR `content` LIKE ?)';
                $_param = array($_lang, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
                $data['data']['_list'] = $this->db->query($_sql, $_param)->result_array();
            }
            else if(empty($keyword) && $parentid > 0){
                $data['data']['_list'] = $this->db->from('article_item')->where(array('lang' => $_lang))->where(array('parentid' => $parentid))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
            }
            else if(!empty($keyword) && $parentid > 0){
                $_sql = 'SELECT * FROM `'.HHV_DB_PREFIX.'article_item` WHERE `lang` = ? AND `parentid` = ? AND (`title` LIKE ? OR `description` LIKE ? OR `content` LIKE ?) ORDER BY `'.$sort['field'].'` '.$sort['value'].' LIMIT '.(($page-1) * $config['per_page']).', '.$config['per_page'];
                $_param = array($_lang, $parentid, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
                $data['data']['_list'] = $this->db->query($_sql, $_param)->result_array();
            }
            else{
                $data['data']['_list'] = $this->db->from('article_item')->where(array('lang' => $_lang))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
            }
        }
        
        $data['data']['_config'] = $config;
        $data['data']['_page'] = $page;
        $data['data']['_sort'] = $sort;
        $data['data']['_keyword'] = $keyword;
        $data['data']['_parentid'] = $parentid;
        $data['seo']['title'] = "Bài viết";
        $data['data']['auth'] = $this->auth;
        $data['data']['_show']['parentid'] = $this->my_nestedset->dropdown('article_category', NULL, 'item');
        $this->my_layout->view("backend/article/item", isset($data)?$data:NULL);
    }

    /*
    ******** Add new article
    **********************************************/
    public function additem()
    {
        $this->my_auth->allow($this->auth, 'backend/article/additem');
       
        if($this->input->post('add')){
            $_post = $this->input->post('data');

            $this->form_validation->set_error_delimiters('<li>', '</li>');
            $this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
            $this->form_validation->set_rules('data[parentid]', 'Node cha', 'trim|required|is_natural_no_zero');
            if (isset($_post['route']) && !empty($_post['route'])) {
                $this->form_validation->set_rules('data[route]', 'Url tùy biến', 'trim|required|callback__route');
                $_post['route'] = $this->my_string->alias($_post['route']);
            }       
            if ($this->form_validation->run() == TRUE){
                $_post = $this->my_string->allow_post($_post, array('title', 'parentid', 'tags', 'image', 'description', 'content', 'publish', 'highlight', 'timer', 'source', 'route', 'meta_title', 'meta_keywords', 'meta_description'));
                $_post['timer'] = !empty($_post['timer'])?gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-',$_post['timer'])) + 7*3600 ):'';
                $_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                $_post['userid_created'] = $this->auth['id'];
                $_post['tags'] = !empty($_post['tags'])?(','.str_replace(', ', ',', $_post['tags']).','):'';
                $_post['alias'] = $this->my_string->alias($_post['title']);
                $_post['lang'] = $this->session->userdata('_lang');
                $this->db->insert('article_item', $_post); 
                $this->my_tag->insert_list($_post['tags']);
                if (isset($_post['route']) && !empty($_post['route'])) {
                    $this->my_route->insert(array(
                        'url' => $_post['route'],
                        'param' => 'article/item/'.$this->db->insert_id(),
                        'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
                    ));
                }
                $this->my_string->js_redirect('Thêm bài viết thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/article/item');
            }
        }
        else{
            $_post['publish'] = 1;
            $data['data']['_post'] = $_post; 
        }
        $data['data']['_show']['parentid'] = $this->my_nestedset->dropdown('article_category', NULL, 'item');
        $data['seo']['title'] = "Thêm bài viết";
        $data['data']['auth'] = $this->auth;  
        $this->my_layout->view("backend/article/additem", isset($data)?$data:NULL);
    }

    /*
    ******** Edit article item
    **********************************************/
    public function edititem($id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/edititem');

        $id = (int)$id;
        $continue = $this->input->get('continue');
        $item = $this->db->where(array('id' => $id))->from('article_item')->get()->row_array();
        if(!isset($item) || count($item) == 0)
            $this->my_string->php_redirect(base_url().'backend');
        if($item['lang'] != $this->session->userdata('_lang'))
            $this->my_string->js_redirect('Ngôn ngữ không phù hợp!', base_url().'backend/article/item');

        if($this->input->post('edit')){
            $_post = $this->input->post('data');
            $data['data']['_post'] = $_post; 

            $this->form_validation->set_error_delimiters('<li>', '</li>');
            $this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
            $this->form_validation->set_rules('data[parentid]', 'Node cha', 'trim|required|is_natural_no_zero');
            if (isset($_post['route']) && !empty($_post['route'])) {
                $this->form_validation->set_rules('data[route]', 'Url tùy biến', 'trim|required|callback__route['.$item['route'].']');
                $_post['route'] = $this->my_string->alias($_post['route']);
            }  
            if ($this->form_validation->run() == TRUE){
                $_post = $this->my_string->allow_post($_post, array('title', 'parentid', 'tags', 'image', 'description', 'content', 'publish', 'highlight', 'timer', 'source', 'route', 'meta_title', 'meta_keywords', 'meta_description'));
                $_post['timer'] = !empty($_post['timer'])?gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-',$_post['timer'])) + 7*3600 ):'';
                $_post['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                $_post['userid_updated'] = $this->auth['id'];
                $_post['tags'] = !empty($_post['tags'])?(','.str_replace(', ', ',', $_post['tags']).','):'';
                $_post['alias'] = $this->my_string->alias($_post['title']);
                $this->db->where(array('id' => $id))->update('article_item', $_post);
                $this->my_tag->insert_list($_post['tags']);
                $this->my_route->update('article/item/'.$id,$_post['route']);
                $this->my_string->js_redirect('Sửa bài viết thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/article/item');
            }
        }
        else{
            $item['tags'] = !empty($item['tags'])?(str_replace(',', ', ', substr(substr($item['tags'], 1), 0, -1))):'';
            $item['timer'] = ($item['timer'] != '0000-00-00 00:00:00')?gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-',$item['timer'])) + 7*3600 ):'';
            $data['data']['_post'] = $item; 
        }
        $data['data']['_show']['parentid'] = $this->my_nestedset->dropdown('article_category', NULL, 'item');
        $data['seo']['title'] = "Sửa bài viết";
        $data['data']['auth'] = $this->auth;  
        $this->my_layout->view("backend/article/edititem", isset($data)?$data:NULL);
    }

    public function _route($route, $old_route){
        return $this->my_route->check_route($route, isset($old_route)?$old_route:NULL);
    }

    /*
    ******** Delete articles
    **********************************************/
    public function delitem($id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/delitem');
        
        $id = (int)$id;
        $continue = $this->input->get('continue');
        $item = $this->db->where(array('id' => $id))->from('article_item')->get()->row_array();
        if(!isset($item) || count($item) == 0){
            $this->my_string->php_redirect(base_url().'backend/article/item');
        }
        if($item['lang'] != $this->session->userdata('_lang'))
            $this->my_string->js_redirect('Ngôn ngữ không phù hợp!', base_url().'backend/article/item');

        $this->db->delete('article_item', array('id' => $id)); 
        $this->my_string->js_redirect('Xóa bài viết thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/article/item');
    }

    /*
    ******** Set display status
    **********************************************/
    public function setitem($field, $id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/setitem');
        $id = (int)$id;
        $continue = $this->input->get('continue');
        $item = $this->db->where(array('id' => $id))->from('article_item')->get()->row_array();
        if(!isset($item) || count($item) == 0)
            $this->my_string->php_redirect(!empty($continue)?base64_decode($continue):base_url().'backend/article/item');
        if(!isset($item[$field]))
            $this->my_string->php_redirect(!empty($continue)?base64_decode($continue):base_url().'backend/article/item');
        $this->db->where(array('id' => $id))->update('article_item', array($field => (($item[$field] == 1)?0:1)));
        $this->my_string->js_redirect('Thay đổi trạng thái thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/article/item');
    }

    /**********************************************
    ******** Article Land
    **********************************************/

    public function itemland($page = 1)
    {
        $this->my_auth->allow($this->auth, 'backend/article/itemland');

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

                $this->db->update_batch('article_item_land', $_data, 'id'); 
                $this->my_string->js_reload('Cập nhật vị trí thành công!');
            }
        }

        /***
        * Choose delete multiple
        ***/
        if($this->input->post('del')){
            $_checkbox = $this->input->post('checkbox');
            if (isset($_checkbox) && count($_checkbox) >= 2) {
                $_temp = NULL;
                foreach ($_checkbox as $keyCheckbox => $valCheckbox) {
                    $_temp[] = $keyCheckbox;
                }
                if (count($_temp)) {
                    $this->db->where_in('id', $_temp)->delete('article_item_land');
                    $this->my_string->js_reload('Xóa lựa chọn thành công!');
                }
            }
            else
                $this->my_string->js_reload('Chọn nhiều hơn 2 đối tượng để xóa!');
        }
        $_lang = $this->session->userdata('_lang');
        $keyword = $this->input->get('keyword');
        $parentid = (int)$this->input->get('parentid');
        $sort = $this->my_common->sort_orderby($this->input->get('sort_field'), $this->input->get('sort_value'));
        $config = $this->my_common->backend_pagination();
        $config['base_url'] = base_url().'backend/article/itemland';

        /***
        * Load pagination when search
        ***/
        if(!empty($keyword) && $parentid == 0){
            $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'article_item_land WHERE `lang` = ? AND (`title` LIKE ? OR `description` LIKE ? OR `content` LIKE ?)';
            $_param = array($_lang, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
            $config['total_rows'] = $this->db->query($_sql, $_param)->num_rows();
        }

        else if(empty($keyword) && $parentid > 0){
            $config['total_rows'] = $this->db->from('article_item_land')->where(array('lang' => $_lang))->where(array('parentid' => $parentid))->count_all_results();
        }
        else if(!empty($keyword) && $parentid > 0){
            $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'article_item_land WHERE `lang` = ? AND `parentid` = ? AND (`title` LIKE ? OR `description` LIKE ? OR `content` LIKE ?)';
            $_param = array($_lang, $parentid, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
            $config['total_rows'] = $this->db->query($_sql, $_param)->num_rows();
        }
        else{
            $config['total_rows'] = $this->db->from('article_item_land')->where(array('lang' => $_lang))->count_all_results();
        }

        // Trang này ko có data. Load trang trước của nó
        $_totalpage = ceil($config['total_rows']/$config['per_page']);
        $page = ($page > $_totalpage)?$_totalpage:$page;

        $config['uri_segment'] = 4; 
        $config['suffix'] = (isset($sort) && count($sort))?'?sort_field='.$sort['field'].'&sort_value='.$sort['value']:'';
        $config['suffix'] = $config['suffix'].(($parentid > 0)?'&parentid'.$parentid:'');
        $config['suffix'] = $config['suffix'].(!empty($keyword)?'&keyword='.$keyword:'');
        $config['first_url'] = $config['base_url'].$config['suffix'];

        /***
        * Load data when search
        ***/
        if ($config['total_rows'] > 0) {

             $this->pagination->initialize($config); 

            /***
            * Create pagination
            ***/
            $data['data']['pagination'] = $this->pagination->create_links();

            if(!empty($keyword) && $parentid == 0){
                $_sql = 'SELECT * FROM '.HHV_DB_PREFIX.'article_item_land WHERE `lang` = ? AND (`title` LIKE ? OR `description` LIKE ? OR `content` LIKE ?)';
                $_param = array($_lang, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
                $data['data']['_list'] = $this->db->query($_sql, $_param)->result_array();
            }
            else if(empty($keyword) && $parentid > 0){
                $data['data']['_list'] = $this->db->from('article_item_land')->where(array('lang' => $_lang))->where(array('parentid' => $parentid))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
            }
            else if(!empty($keyword) && $parentid > 0){
                $_sql = 'SELECT * FROM `'.HHV_DB_PREFIX.'article_item_land` WHERE `lang` = ? AND `parentid` = ? AND (`title` LIKE ? OR `description` LIKE ? OR `content` LIKE ?) ORDER BY `'.$sort['field'].'` '.$sort['value'].' LIMIT '.(($page-1) * $config['per_page']).', '.$config['per_page'];
                $_param = array($_lang, $parentid, '%'.$keyword.'%', '%'.$keyword.'%', '%'.$keyword.'%');
                $data['data']['_list'] = $this->db->query($_sql, $_param)->result_array();
            }
            else{
                $data['data']['_list'] = $this->db->from('article_item_land')->where(array('lang' => $_lang))->limit($config['per_page'], ($page-1) * $config['per_page'])->order_by($sort['field'].' '.$sort['value'])->get()->result_array();
            }
        }
        
        $data['data']['_config'] = $config;
        $data['data']['_page'] = $page;
        $data['data']['_sort'] = $sort;
        $data['data']['_keyword'] = $keyword;
        $data['data']['_parentid'] = $parentid;
        $data['seo']['title'] = "Bài viết";
        $data['data']['auth'] = $this->auth;
        $data['data']['_show']['parentid'] = $this->my_nestedset->dropdown('article_category', NULL, 'item');
        $this->my_layout->view("backend/article/itemland", isset($data)?$data:NULL);
    }

    /*
    ******** Add new article land
    **********************************************/
    public function additemland()
    {
        $this->my_auth->allow($this->auth, 'backend/article/additemland');
       
        if($this->input->post('add')){
            $_post = $this->input->post('data');

            $this->form_validation->set_error_delimiters('<li>', '</li>');
            $this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
            $this->form_validation->set_rules('data[parentid]', 'Node cha', 'trim|required|is_natural_no_zero');
            if (isset($_post['route']) && !empty($_post['route'])) {
                $this->form_validation->set_rules('data[route]', 'Url tùy biến', 'trim|required|callback__route');
                $_post['route'] = $this->my_string->alias($_post['route']);
            }       
            if ($this->form_validation->run() == TRUE){
                $_post = $this->my_string->allow_post($_post, array('title', 'parentid', 'tags', 'image', 'content', 'publish', 'highlight', 'timer', 'source', 'route', 'meta_title', 'meta_keywords', 'meta_description'));
                $_post['timer'] = gmdate('Y-m-d H:i:s', strtotime(str_replace('/', '-',$_post['timer'])) + 7*3600 );
                $_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                $_post['userid_created'] = $this->auth['id'];
                $_post['tags'] = !empty($_post['tags'])?(','.str_replace(', ', ',', $_post['tags']).','):'';
                $_post['alias'] = $this->my_string->alias($_post['title']);
                $_post['lang'] = $this->session->userdata('_lang');
                $this->db->insert('article_item_land', $_post); 
                $this->my_tag->insert_list($_post['tags']);
                $this->my_route->insert(array(
                    'url' => $_post['route'],
                    'param' => 'article/itemland/'.$this->db->insert_id(),
                    'created' => gmdate('Y-m-d H:i:s', time() + 7*3600),
                ));
                $this->my_string->js_redirect('Thêm bài viết thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/article/itemland');
            }
        }
        else{
            $_post['publish'] = 1;
            $data['data']['_post'] = $_post; 
        }
        $data['data']['_show']['parentid'] = $this->my_nestedset->dropdown('article_category', NULL, 'item');
        $data['seo']['title'] = "Thêm bài viết";
        $data['data']['auth'] = $this->auth;  
        $this->my_layout->view("backend/article/additemland", isset($data)?$data:NULL);
    }

    /*
    ******** Edit article land
    **********************************************/
    public function edititemland($id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/edititemland');

        $id = (int)$id;
        $continue = $this->input->get('continue');
        $item = $this->db->where(array('id' => $id))->from('article_item_land')->get()->row_array();
        if(!isset($item) || count($item) == 0)
            $this->my_string->php_redirect(base_url().'backend');
        if($item['lang'] != $this->session->userdata('_lang'))
            $this->my_string->js_redirect('Ngôn ngữ không phù hợp!', base_url().'backend/article/itemland');

        if($this->input->post('edit')){
            $_post = $this->input->post('data');
            $data['data']['_post'] = $_post; 

            $this->form_validation->set_error_delimiters('<li>', '</li>');
            $this->form_validation->set_rules('data[title]', 'Tiêu đề', 'trim|required');
            $this->form_validation->set_rules('data[parentid]', 'Node cha', 'trim|required|is_natural_no_zero');
            if (isset($_post['route']) && !empty($_post['route'])) {
                $this->form_validation->set_rules('data[route]', 'Url tùy biến', 'trim|required|callback__route['.$item['route'].']');
                $_post['route'] = $this->my_string->alias($_post['route']);
            }  
            if ($this->form_validation->run() == TRUE){
                $_post = $this->my_string->allow_post($_post, array('title', 'parentid', 'tags', 'image', 'content', 'publish', 'highlight', 'timer', 'source', 'route', 'meta_title', 'meta_keywords', 'meta_description'));
                $_post['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                $_post['userid_updated'] = $this->auth['id'];
                $_post['tags'] = !empty($_post['tags'])?(','.str_replace(', ', ',', $_post['tags']).','):'';
                $_post['alias'] = $this->my_string->alias($_post['title']);
                $this->db->where(array('id' => $id))->update('article_item_land', $_post);
                $this->my_tag->insert_list($_post['tags']);
                if (isset($_post['route']) && !empty($_post['route'])) {
                    $this->my_route->update('article/itemland/'.$id,$_post['route']);
                }
                $this->my_string->js_redirect('Sửa bài viết thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/article/itemland');
            }
        }
        else{
            $item['tags'] = !empty($item['tags'])?(str_replace(',', ', ', substr(substr($item['tags'], 1), 0, -1))):'';
            $data['data']['_post'] = $item; 
        }
        $data['data']['_show']['parentid'] = $this->my_nestedset->dropdown('article_category', NULL, 'item');
        $data['seo']['title'] = "Sửa bài viết";
        $data['data']['auth'] = $this->auth;  
        $this->my_layout->view("backend/article/edititemland", isset($data)?$data:NULL);
    }

    // public function _route($route, $old_route){
    //     return $this->my_route->check_route($route, isset($old_route)?$old_route:NULL);
    // }

    /*
    ******** Delete articles
    **********************************************/
    public function delitemland($id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/delitemland');
        
        $id = (int)$id;
        $continue = $this->input->get('continue');
        $item = $this->db->where(array('id' => $id))->from('article_item_land')->get()->row_array();
        if(!isset($item) || count($item) == 0){
            $this->my_string->php_redirect(base_url().'backend/article/itemland');
        }
        if($item['lang'] != $this->session->userdata('_lang'))
            $this->my_string->js_redirect('Ngôn ngữ không phù hợp!', base_url().'backend/article/itemland');

        $this->db->delete('article_item_land', array('id' => $id)); 
        $this->my_string->js_redirect('Xóa bài viết thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/article/itemland');
    }

    /*
    ******** Set display status
    **********************************************/
    public function setitemland($field, $id)
    {
        $this->my_auth->allow($this->auth, 'backend/article/setitem');
        $id = (int)$id;
        $continue = $this->input->get('continue');
        $item = $this->db->where(array('id' => $id))->from('article_item_land')->get()->row_array();
        if(!isset($item) || count($item) == 0)
            $this->my_string->php_redirect(!empty($continue)?base64_decode($continue):base_url().'backend/article/itemland');
        if(!isset($item[$field]))
            $this->my_string->php_redirect(!empty($continue)?base64_decode($continue):base_url().'backend/article/itemland');
        $this->db->where(array('id' => $id))->update('article_item_land', array($field => (($item[$field] == 1)?0:1)));
        $this->my_string->js_redirect('Thay đổi trạng thái thành công!', !empty($continue)?base64_decode($continue):base_url().'backend/article/itemland');
    }
}
