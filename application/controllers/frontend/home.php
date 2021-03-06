<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->my_layout->setLayout("layout/frontend"); // load file layout chính (views/layout/frontend.php)
        $this->auth = $this->my_auth->check();
    }

    public function index($lang = '')
    {
        get_lang($lang);
        $this->my_layout->view("frontend/home/index");
    }

}
