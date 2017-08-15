<?php
/**
 * 标签控制器
 * @author yangshengkai
 * @time 2017/04/11
 */
class Tags extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $color = ['red', 'black', 'green', 'purple', 'purple', 'blue', 'yellow'];
        $this->load->model('TagsModel');
        $rlt = $this->TagsModel->getTags();
        $data = array(
            'title' => '标签云',
            'colors' => $color,
            'tags' => $rlt
        );

        $this->load->view("templet/header", $data);
        $this->load->view("tags", $data);
        $this->load->view("templet/footer");
    }
}