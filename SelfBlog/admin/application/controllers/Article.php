<?php
/**
 * 文章控制器
 * @author yangshengkai
 * @time 2017/05/07
 */

class Article extends MY_Controller
{
    public function __construct ()
    {
        parent::__construct();
        $this->load->helper(array('text','form'));
    }

    /**
     * 读取文章列表
     * @param int $page
     */
    public function index($page = 0)
    {
        $this->load->library('pagination');
        $this->load->model('ArticleModel');

        //每页显示十条数据
        $limit['num'] = 10;
        $limit['offset'] = $page;
        $limit['type'] = 'post';

        $config['base_url'] = site_url('/article');
        $config['total_rows'] = $this->ArticleModel->getArticlesCount();//数据总条数
        $config['per_page'] = $limit['num'];//每页显示条数

        $this->pagination->initialize($config);
        $pages = $this->pagination->create_links();

        $data = array(
            'articles' => $this->ArticleModel->getArticle($limit),
            'pages' => $pages
        );

        $this->load->view('article/article_list', $data);
    }

    /**
     * 新建文章
     */
    public function createArticle()
    {
        $this->load->model('CategoryModel');
        $cates = $this->CategoryModel->getAllCates();
        $data = array(
            'cates' => $cates
        );

        $this->load->view('article/article_create', $data);
    }

    /**
     * 编辑文章
     */
    public function editArticle()
    {
        $this->load->model('ArticleModel');
        $this->load->model('CategoryModel');

        $cid = $this->uri->segment(3);

        $data = array(
            //取出URL中的文章id
            'article' => $this->ArticleModel->getOneArticle($cid),
            'all_cate' => $this->CategoryModel->getAllCates()
        );

        $this->load->view('article/article_edit',$data);
    }

    /**
     * 新建文章
     */
    public function addNewArticle()
    {
        $this->load->model('ArticleModel');
        $this->load->model('CategoryModel');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', '文章标题', 'trim|required',
            array(
                'required' => '必须填写文章标题!'
            )
        );
        $this->form_validation->set_rules('contents', '文章内容', 'required',
            array(
                'required' => '必须填写文章内容!'
            )
        );

        if($this->form_validation->run() === FALSE) {
            $this->createArticle();
        } else {

            $data = array(
                'mid' => intval($this->input->post('category')),
                'title' => $this->input->post('title'),
                'posttime' => $this->input->post('datetimepicker'),
                'author' => 'Skye',
                'content' => $this->input->post('contents'),
                'type' => 'post'
            );
            $query = $this->ArticleModel->insertArticle($data);

            $cid = $this->db->insert_id();

            $mid = $this->input->post('category');
            $fmids = $this->CategoryModel->getAllFatherMid($mid);
            $postNum = $this->CategoryModel->getPostNum($fmids);
            foreach ($postNum as &$value){
                $value["postcount"] = $value["postcount"] + 1;
            }

            $rlt = $this->CategoryModel->updatePostNum($postNum);

            $back = array(
                'title' => $data['title'],
                'echo' => '保存文章成功！',
                'cid' => $cid
            );
            if($query && $rlt) {
                $this->load->view('sucessful', $back);
            }
        }
    }

    /**
     * 编辑文章
     */
    public function updateArticle()
    {
        $cid = $this->uri->segment(3);
        $this->load->model('ArticleModel');
        $article = array(
            'title' => $this->input->post('title'),
            'content' => $this->input->post('contents'),
            'posttime' => $this->input->post('datetimepicker'),
            'mid' => $this->input->post('category')
        );

        $ack = $this->ArticleModel->updateArticle($cid, $article);

        if($ack) {
            $back = array(
                'title' => $article['title'],
                'echo' => '保存修改成功！',
                'cid' => $cid
            );

            $this->load->view('sucessful',$back);
        }
        else {
            echo '文章更新失败';
        }
    }

    /**
     * 删除文章
     */
    public function delArticle()
    {
        /**
         * 后续需要重构，把 删文章 和 减发布数 独立开来
         */
        $this->load->model('ArticleModel');
        $idsdata = $this->input->post('ids');
        if(!empty($idsdata)) {
            $rlt = $this->ArticleModel->deleteArticle($idsdata);
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