<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("my_layout"); // Sử dụng thư viện layout
        $this->my_layout->setLayout("layout/frontend"); // load file layout chính (views/layout/frontend.php)
    }

    public function index($id = '', $page = 1)
    {
        $data['data']['category'] = $this->db->where(array('parentid' => '2'))->from('article_category')->get()->result_array();
        if(isset($id) && !empty($id)){
            $data['data']['item'] = $this->db->where(array('id' => $id))->from('article_item')->get()->result_array();
            $data['data']['namecategory'] = $this->db->select('article_category.title')->where(array('article_item.id' => $id))->from('article_item')->join('article_category', 'article_category.id=article_item.parentid')->get()->result_array();
        }
        else{
            $data['data']['item'] = $this->db->from('article_item')->get()->result_array();
            $data['data']['namecategory'] = $this->db->select('article_category.title')->where(array('article_item.id' => $id))->from('article_item')->join('article_category', 'article_category.id=article_item.parentid')->get()->result_array();
        }


        $keyword = $this->input->get('keyword');
        $config = $this->my_common->backend_pagination();
        $config['base_url'] = base_url().'frontend/article/index';

        /***
        * Load pagination when search
        ***/
        if(!empty($keyword)){
            $config['total_rows'] = $this->db->from('article_item')->like('title', $keyword)->count_all_results();
        }
        else{
            $config['total_rows'] = $this->db->from('article_item')->count_all_results();
        }

        // Trang này ko có data. Load trang trước của nó
        $_totalpage = ceil($config['total_rows']/$config['per_page']);
        $page = ($page > $_totalpage)?$_totalpage:$page;

        $config['uri_segment'] = 4; 
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
                $data['data']['_list'] = $this->db->from('article_item')->like('title', $keyword)->limit($config['per_page'], ($page-1) * $config['per_page'])->get()->result_array();
            }
            else{
                $data['data']['_list'] = $this->db->from('article_item')->limit($config['per_page'], ($page-1) * $config['per_page'])->get()->result_array();
            }
        }
        $data['data']['_config'] = $config;
        $data['data']['_page'] = $page;
        $data['data']['_keyword'] = $keyword;

        $this->my_layout->view('frontend/article/index', isset($data)?$data:NULL);
    }

    public function view($id = '')
    {
        $data['data']['category'] = $this->db->where(array('parentid' => '2'))->from('article_category')->get()->result_array();
        if(isset($id) && !empty($id)){
            $data['data']['item'] = $this->db->where(array('parentid' => $id))->from('article_item')->get()->result_array();
            $data['data']['namecategory'] = $this->db->select('title')->where(array('id' => $id))->from('article_category')->get()->result_array();
        }
        $this->my_layout->view('frontend/article/view', isset($data)?$data:NULL);
    }
}
