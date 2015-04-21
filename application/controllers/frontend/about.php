<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->my_layout->setLayout("layout/frontend");
        $this->auth = $this->my_auth->check(); 
    }

    /*
    ******** About
    **********************************************/
    public function index($lang = '')
    {
        get_lang($lang);
        $this->my_layout->view("frontend/about/index");
    }
}