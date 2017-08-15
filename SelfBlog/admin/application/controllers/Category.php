<?php
/**
 * 分类控制器
 * @author yangshengkai
 * @time 2017/04/11
 */

class Category extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->model('CategoryModel');
        $cates = $this->CategoryModel->getCates();

        $data = array(
            'cates' => $cates
        );
        $this->load->view("category/category_manage", $data);
    }

    /**
     * 新增分类
     */
    public  function createCate()
    {
        $this->load->model('CategoryModel');

        $data = array(
            'categories' => $this->CategoryModel->getAllCates()
        );

        $this->load->view('category/category_add', $data);
    }

    /**
     * 添加分类
     */
    public function addCategory()
    {
        $this->load->model('CategoryModel');
        $this->form_validation->set_rules('name', '分类名称', 'trim|required',
            array(
                'required' => '必须填写分类名称!'
            )
        );

        if($this->form_validation->run() === FALSE) {
            $this->load->view('category_add');
        } else {
            //POST成功后，获取文章相关内容，进行数据库写入操作
            $data = array(
                'pmid' => $this->input->post('pmid'),
                'name' => $this->input->post('name'),
                'postcount' => 0,
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description'),
                'addtime' => date('Y-m-d H:i:s')
            );

            $query = $this->CategoryModel->insertCategory($data);
            if($query) {
                redirect('category');
            }
        }
    }

    /**
     * 删除分类
     */
    public function delCate()
    {
        $mids = $this->input->get_post('ids');
        $this->load->model('CategoryModel');
        $rlt = $this->CategoryModel->delCate($mids);
        if ($rlt){
            $this->renderOutput(array(), 0, '删除成功');
        }else {
            $this->renderOutput(array(), 1, '删除失败');
        }
    }

}