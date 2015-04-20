<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->my_layout->setLayout("layout/frontend");
    }

    /*
    ******** About
    **********************************************/
    public function index()
    {
        $this->my_layout->view("frontend/about/index");
    }
}