<?php
/**
 * 链接控制器
 * @author yangshengkai
 * @time 2017/04/11
 */
class Links extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->config->load('pagination',true);
    }
    public function index($page = 0)
    {
        $this->load->model('LinksModel');

        $links = $this->LinksModel->getLinks();

        $data = array(
            'links' => $links
        );
        $this->load->view('links/links_list', $data);
    }

    /**
     * 增加链接
     */
    public function create()
    {
        $this->load->view('links/links_create');
    }
    public function addLink()
    {
        $this->load->model('LinksModel');
        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('bloger', 'bloger', 'required',
            array(
                'required' => '必须填写作者!'
            )
        );
        $this->form_validation->set_rules('url', 'url', 'required',
            array(
                'required' => '必须填写博客链接!'
            )
        );
        $this->form_validation->set_rules('description', 'description','required',
            array(
                'required' => '必须填写描述!',
            )
        );
        $this->form_validation->set_rules('type', 'type','required',
            array(
                'required' => '必须选择类型!'
            )
        );

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = $this->input->get_post();

            $data_insert['bloger'] = $data['bloger'];
            $data_insert['url'] = $data['url'];
            $data_insert['description'] = $data['description'];
            $data_insert['type'] = $data['type'];

            $ack = $this->LinksModel->insertLink($data_insert);
            if($ack) {
                redirect('links');
            } else {
                echo '<script>alert("添加链接失败！");</script>';
            }
        }
    }

    /**
     * 加载链接编辑界面
     */
    public function editLink()
    {
        $this->load->model('LinksModel');
        $id = $this->uri->segment(3);

        $linkinfo = $this->LinksModel->getLink($id);
        if(!is_null($linkinfo)) {
            $data = array(
                'link' => array(
                    'id' => $linkinfo['id'],
                    'bloger' => $linkinfo['bloger'],
                    'url' => $linkinfo['url'],
                    'description' => $linkinfo['description'],
                    'type' => $linkinfo['type']
                )
            );
            $this->load->view('links/link_edit', $data);
        } else {
            redirect('links');
        }
    }

    /**
     * 更新链接
     * @param $id
     */
    public function updateLink($id)
    {
        $this->load->model('LinksModel');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->form_validation->set_rules('bloger', 'bloger', 'required',
            array(
                'required' => '必须填写作者!'
            )
        );
        $this->form_validation->set_rules('url', 'url', 'required',
            array(
                'required' => '必须填写博客链接!'
            )
        );
        $this->form_validation->set_rules('description', 'description','required',
            array(
                'required' => '必须填写描述!',
            )
        );
        $this->form_validation->set_rules('type', 'type','required',
            array(
                'required' => '必须选择类型!'
            )
        );

        if ($this->form_validation->run() == false) {
            $this->editLink();
        }else {
            $data = $this->input->get_post();

            $data_update['id'] = $id;
            $data_update['bloger'] = $data['bloger'];
            $data_update['url'] = $data['url'];
            $data_update['description'] = $data['description'];
            $data_update['type'] = $data['type'];

            $ack = $this->LinksModel->updateLink($data_update);
            if($ack) {
                redirect('links');
            }else {
                echo '<script>alert("链接信息修改失败！");<script>';
            }
        }
    }

    /**
     * 删除链接
     */
    public function delLink(){
        $ids = $this->input->get_post('ids');

        $this->load->model('LinksModel');

        if(!empty($ids)) {
            $rlt = $this->LinksModel->deleteLinks($ids);
            if ($rlt){
                $this->renderOutput(array(), 0, '删除成功');
            }else {
                $this->renderOutput(array(), 2, '删除失败');
            }
        }else {
            $this->renderOutput(array(), 1, '未选择要删除的数据');
        }
    }

}