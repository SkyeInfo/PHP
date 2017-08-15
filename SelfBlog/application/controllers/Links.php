<?php
/**
 * 链接控制器
 * @author yangshengkai
 * @time 2017/04/11
 */
class Links extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
    }
    public function index($page = 0)
    {
        $this->load->library('pagination');
        $this->load->model('LinksModel');

        //每页显示十条数据
        $limit['num'] = 10;
        $limit['offset'] = $page;

        $config['base_url'] = site_url('/links');
        $config['next_link'] = '下一页';
        $config['prev_link'] = '上一页';
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['total_rows'] = $this->LinksModel->getLinksNum();//数据总条数
        $config['per_page'] = $limit['num'];//每页显示条数

        $this->pagination->initialize($config);
        $pages = $this->pagination->create_links();

        $data['linksinfo'] = $this->LinksModel->getLinks($limit);
        $data['title'] = '友情链接';
        $data['pages'] = $pages;

        $this->load->view("templet/header", $data);
        $this->load->view("links", $data);
        $this->load->view("templet/footer");
    }
}