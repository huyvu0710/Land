<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("my_layout"); // Sử dụng thư viện layout
        $this->my_layout->setLayout("layout/frontend"); // load file layout chính (views/layout/frontend.php)
    }

    public function index($lang = '')
    {
        if(empty($lang)){
            $_lang = $this->session->userdata('_lang');
            if (isset($_lang) && !empty($_lang) && $_lang != 'vi') {
                $this->session->set_userdata('_lang', 'vi');
            }
        }
        else{
            if (!empty($lang) && in_array($lang, array('jp', 'en'))) {
                $this->session->set_userdata('_lang', $lang);
            }
        }
        $_lang = $this->session->userdata('_lang');
        $this->lang->load('frontend', $_lang); // $this->lang->load('filename', 'language'); filename: frontend_lang
        $this->my_layout->view("frontend/home/index");
    }

}
