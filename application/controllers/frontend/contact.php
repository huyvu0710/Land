<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller
{
    private $auth;
    public function __construct()
    {
        parent::__construct();
        $this->my_layout->setLayout("layout/frontend"); // load file layout chính (views/layout/frontend.php)
        $this->auth = $this->my_auth->check();
    }

    public function index($lang = '')
    {
        if($this->input->post('submit')){
            $_post = $this->input->post('data');
            $data['data']['_post'] = $_post; 

            $this->form_validation->set_error_delimiters('<li>', '</li>');
            $this->form_validation->set_rules('data[fullname]', 'Tên đầy đủ', 'trim|required');
            $this->form_validation->set_rules('data[email]', 'Email', 'trim|required|valid_email|callback__email');
            $this->form_validation->set_rules('data[phone]', 'Số điện thoại', 'trim|required|numeric');
            if ($this->form_validation->run() == TRUE){
                $_post = $this->my_string->allow_post($_post, array('fullname', 'email', 'message', 'phone'));
                $_post['readed'] = 0;
                $_post['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                $_post['userid_created'] = $this->auth['id'];
                $this->db->insert('contact', $_post); 
                $this->my_string->js_redirect('Gửi bài thành công!', base_url().'frontend/contact/index');
            }
        }

        get_lang($lang);
        $this->my_layout->view("frontend/contact/index", isset($data)?$data:NULL);
    } 
}
