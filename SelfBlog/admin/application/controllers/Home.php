<?php
/**
 * 主页控制器
 * @author yangshengkai
 * @time 2017/04/11
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $this->load->model('LinksModel');
        $data['linksinfo'] = $this->LinksModel->getLinks();
        $this->load->view("header");
        $this->load->view("sidebar");
        $this->load->view("home", $data);
        $this->load->view("footer");
    }
}