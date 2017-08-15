<?php
/**
 * About控制器
 * @author yangshengkai
 * @time 2017/05/07
 */

class About extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('text','form'));
    }

    public function index()
    {
        $this->load->model('ArticleModel');
        $data = array(
            'pages' => $this->ArticleModel->getTypeArticles('page')
        );

        if(count($data['pages']) > 0) {
            $this->load->view('about/about_list',$data);
        } else {
            $this->load->view('about/about_create');
        }
    }

    /**
     * 新建“关于”
     */
    public function addNewPage()
    {
        $this->load->model('ArticleModel');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('contents', '文章内容', 'required',
            array(
                'required' => '必须填写文章内容!'
            )
        );

        if($this->form_validation->run() === FALSE) {
            echo '表单验证没有通过';
        } else {
            //POST成功后，获取文章相关内容，进行数据库写入操作
            $data = array(
                'title' => 'about',
                'posttime' => date('Y-m-d H:i:s'),
                'author'=>'Skye',
                'content'=>$this->input->post('contents'),
                'type'=>'page'
            );
            $query = $this->ArticleModel->insertArticle($data);

            $back = array(
                'title'=>$data['title'],
                'echo'=>'保存文章成功！',
                'cid'=> $this->db->insert_id()
            );

            if($query) {
                $this->load->view('sucessful',$back);
            }
        }
    }

    /**
     * 编辑关于
     * @param bool $cid
     */
    public function editPage($cid = false)
    {
        $this->load->model('ArticleModel');

        $arr = array(
            'title' => 'about',
            'posttime' => date('Y-m-d G:i:s'),
            'author' => 'Skye',
            'content' => '关于',
            'type' => 'page'
        );

        if(!$cid) {
            $data = array(
                'page' => $arr
            );
            $this->load->view('about/about_edit', $data);
        }else {
            $data = array(
                'page' => $this->ArticleModel->getTypeArticles('page', 'about')
            );
            $this->load->view('about/about_edit', $data);
        }
    }

    /**
     * 更新“关于”
     */
    public function updatePage()
    {
        $this->load->model('ArticleModel');
        $cid = $this->uri->segment(3);

        $data = array(
            'title' => $this->input->post('title'),
            'content' => $this->input->post('contents'),
            'posttime' => date('Y-m-d H:i:s'),
            'type' => 'page'
        );

        $ack = $this->ArticleModel->updateAbout($cid, $data);

        if($ack) {
            $back = array(
                'title' => $data['title'],
                'echo' => '保存修改成功！',
                'cid' => $data['cid']
            );
            $this->load->view('sucessful',$back);
        }else {
            echo '文章更新失败';
        }
    }
}