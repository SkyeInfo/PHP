<?php
/**
 * AboutMe控制器
 * @author yangshengkai
 * @time 2017/04/23
 */

class AboutMe extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model('ArticleModel');

        $rlt = $this->ArticleModel->checkExist('', 'page');

        if($rlt) {
            $page = $this->ArticleModel->getAboutPage();
            $page['title'] = '关于我';
            $data = array(
                'title'=>'关于我',
                'article'=>$page,
                'cid' => $page['cid']
            );

            $this->load->view("templet/header", $data);
            $this->load->view('about_me', $data);
            $this->load->view("templet/pinglunme");
            $this->load->view("templet/footer");
        }
    }
}