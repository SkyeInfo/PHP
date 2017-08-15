<?php
/**
 * 主页控制器
 * @author yangshengkai
 * @time 2017/04/23
 */

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('home');
    }
}