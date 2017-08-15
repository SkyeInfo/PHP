<?php
/**
 * Article控制器
 * @author yangshengkai
 * @time 2017/04/23
 */

class Article extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * index方法
     * @param int $page 可看做 offset
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
        $config['next_link'] = '下一页';
        $config['prev_link'] = '上一页';
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['total_rows'] = $this->ArticleModel->getArticlesNum();//数据总条数
        $config['per_page'] = $limit['num'];//每页显示条数

        $this->pagination->initialize($config);
        $pages = $this->pagination->create_links();
        $data = array(
            'title' => '文章列表',
            'articles' => $this->ArticleModel->getLimitArticles($limit),
            'pages' => $pages
        );

        if(count($data['articles']) == 0) {
            $this->load->view("templet/header", $data);
            $this->load->view('noart', $data);
            $this->load->view("templet/footer");
        }else {
            $this->load->view("templet/header", $data);
            $this->load->view('blogs', $data);
            $this->load->view("templet/footer");
        }
    }

    /**
     * 获取文章
     */
    public function archive()
    {
        $this->load->model('ArticleModel');

        $cid = $this->uri->segment(2);

        if(is_numeric($cid)) {
            $article = $this->ArticleModel->getOneArticle($cid);

            if($article['type'] == 'page') {
                redirect('article');
                die();
            }

            if($article) {
                $data = array(
                    'title' => $article['title'],
                    'posttime' => $article['posttime'],
                    'author' => $article['author'],
                    'content' => $article['content'],
                    'cid' => $cid
                );

                $this->load->view("headerPost", $data);
                $this->load->view("post", $data);
                $this->load->view("templet/pinglun", $data);
                $this->load->view("templet/footer");

            }else {
				$this->load->view("templet/header", $data);
				$this->load->view('noart', $data);
				$this->load->view("templet/footer");
            }
        }else {
			$this->load->view("templet/header", $data);
			$this->load->view('noart', $data);
			$this->load->view("templet/footer");
        }
    }

    /**
     * 通过分类获取
     * @param int $page
     */
    public function cate($page = 0)
    {
        $this->load->library('pagination');
        $this->load->model('ArticleModel');

        $metaName = $this->uri->segment(1);
        if (strpos($metaName, '.html') !== false){
            $temp = explode('.',$metaName);
            $metaName = $temp[0];
        }

        $meta = $this->getMidByUrl($metaName);

        if(is_array($meta) && (count($meta) == 0)) {
            $this->load->view("templet/header", $data);
			$this->load->view('noart', $data);
			$this->load->view("templet/footer");
        }

        //每页显示10条数据
        $limit['num'] = 10;
        $limit['offset'] = $page;
        $limit['mid'] = $meta['mid'];

        $config['base_url'] = site_url($meta['base_url']);
        $config['next_link'] = '下一页';
        $config['prev_link'] = '上一页';
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['total_rows'] = $this->ArticleModel->getArticlesNumByMid($meta['mid']);//数据总条数
        $config['per_page'] = $limit['num'];//每页显示条数

        $this->pagination->initialize($config);
        $pages = $this->pagination->create_links();

        $data = array(
            'title' => $meta['title'].'随笔',
            'articles' => $this->ArticleModel->getLimitArticlesByMid($limit),
            'pages' => $pages
        );

        if(count($data['articles']) == 0) {
            $this->load->view("templet/header", $data);
            $this->load->view('noart', $data);
            $this->load->view("templet/footer");
        }else {

            $this->load->view("templet/header", $data);
            $this->load->view('blogs', $data);
            $this->load->view("templet/footer");
        }
    }

    /**
     * 获取分类
     * @param $metaName
     * @return array
     */
    private function getMidByUrl($metaName)
    {
        switch($metaName) {
            case 'tech':
                return array('mid'=>2,'title'=>'技术','base_url'=>'tech');
            case 'life':
                return array('mid'=>3,'title'=>'生活','base_url'=>'life');
            default:
                return array();
        }
    }

    /**
     * 通过标签获取文章
     * @param $mid
     * @param int $page
     */
    public function getArticlesByTag($mid, $page = 0)
    {
        if(is_null($mid)) {
			$this->load->view("templet/header", $data);
			$this->load->view('noart', $data);
			$this->load->view("templet/footer");
        }

        $this->load->library('pagination');
        $this->load->model('ArticleModel');

        //每页显示十条数据
        $limit['num'] = 10;
        $limit['offset'] = $page;
        $limit['mid'] = $mid;

        $config['base_url'] = "http://www.skyeinfo.com/arttag.html/$mid";
        $config['next_link'] = '下一页';
        $config['prev_link'] = '上一页';
        $config['first_link'] = '首页';
        $config['last_link'] = '末页';
        $config['total_rows'] = $this->ArticleModel->getArticlesNumByMid($mid);//数据总条数
        $config['per_page'] = $limit['num'];//每页显示条数

        $this->pagination->initialize($config);
        $pages = $this->pagination->create_links();
        $data = array(
            'title' => '文章列表',
            'articles' => $this->ArticleModel->getLimitArticlesByMid($limit),
            'pages' => $pages
        );

        if(count($data['articles']) == 0) {
            $this->load->view("templet/header", $data);
            $this->load->view('noart', $data);
            $this->load->view("templet/footer");
        }else {
            $this->load->view("templet/header", $data);
            $this->load->view('blogs', $data);
            $this->load->view("templet/footer");
        }
    }
}