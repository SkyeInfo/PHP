<?php
/**
 * Article模型
 * @author yangshengkai
 * @time 2017/04/23
 */

class ArticleModel extends CI_Model{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取全部数据
     * @return mixed
     */
    public function getAllArticles()
    {
        $this->db->select('title, posttime, cid');
        $this->db->from('contents');
        $this->db->order_by('posttime', 'DESC');
        $query = $this->db->get()->result_array();

        return $query;
    }

    /**
     * 获取表内数据数量
     * @param $type string 类型
     * @return mixed
     */
    public function getArticlesNum($type = 'post')
    {
        $this->db->where('type', $type);
        $this->db->from('contents');
        $num = $this->db->count_all_results();
        
        return $num;
    }

    /**
     * 获取表内指定分类数据数量
     * @param mid
     * @return mixed
     */
    public function getArticlesNumByMid($mid)
    {

        $mids = $this->getAllChildMid($mid);
        $this->db->from('contents');
        $this->db->where_in('mid', $mids);

        $all_num = $this->db->count_all_results();
        return $all_num;
    }

    /**
     * 递归获取所有的子分类
     * @return array
     */
    public function getAllChildMid($pmid)
    {
        $childMid[] = $pmid;
        $arr_childMid = $this->getChildMid($pmid);

        if (!empty($arr_childMid)) {
            do{
                foreach ($arr_childMid as $val) {
                    $childMid[] = intval($val['mid']);
                }

                $childMid_temp = $arr_childMid;
                $arr_childMid = array();

                foreach ($childMid_temp as $value) {
                    $temp_arr = $this->getChildMid(intval($value['mid']));
                    if (!empty($temp_arr)) {
                        foreach ($temp_arr as $v) {
                            $arr_childMid[] = $v;
                        }
                    }
                }
            }while (!empty($arr_childMid));
        }
        return $childMid;   
    }
    /**
     * 获取父分类下的子分类
     * @return array
     */
    public function getChildMid($pmid)
    {
        $this->db->select('mid');
        $this->db->from('metas');
        $this->db->where('pmid', $pmid);

        $rlt = $this->db->get()->result_array();

        return $rlt;
    }

    /**
     * 获取有限个数的数据
     * @param $limit array
     * @return mixed
     */
    public function getLimitArticles($limit = array('num' => false,'offset' => false,'type' => 'post'))
    {
        if(isset($limit['num']) && isset($limit['offset']) && ($limit['num'] !== false) && ($limit['offset'] !== false)) {
            $this->db->from('contents');
            $this->db->select('title, posttime, cid');
            $this->db->order_by('posttime', 'DESC');
            $this->db->where('type', $limit['type']);
            $this->db->limit($limit['num'], $limit['offset']);

            $query = $this->db->get()->result_array();

            return $query;
        }else {
            return $this->getAllArticles();
        }
    }
    /**
     * 按分类获取数据
     * @param array $limit
     * @return mixed
     */
    public function getLimitArticlesByMid($limit = array('num' => false,'offset' => false,'mid' => false))
    {
        if(isset($limit['num']) && isset($limit['offset']) && ($limit['num'] !== false) && ($limit['offset'] !== false) && isset($limit['mid'])) {

            $mid = $limit['mid'];
            $mids = $this->getAllChildMid($mid);
            $this->db->select('title, posttime, cid, mid');
            $this->db->from('contents');
            $this->db->where_in('mid', $mids);
            $this->db->order_by('posttime', 'DESC');
            $this->db->limit($limit['num'], $limit['offset']);
            $query = $this->db->get()->result_array();

            return $query;
        } else {
            return $this->getAllArticles();
        }
    }

    /**
     * 获取单篇文章
     * @param $cid
     * @return mixed
     */
    public function getOneArticle($cid)
    {
        $this->db->select('*');
        $this->db->from('contents');
        $this->db->where('cid', $cid);

        $query = $this->db->get()->row_array();
        return $query;
    }

    /**
     * 获取“关于”
     * @return mixed
     */
    public function getAboutPage()
    {
        $this->db->select('*');
        $this->db->from('contents');
        $this->db->where('type', 'page');

        $result = $this->db->get()->row_array();

        return $result;
    }

    /**
     * 检查文章是否存在
     * @param string $title
     * @param string $type
     * @return bool
     */
    public function checkExist($title = '', $type = 'post')
    {
        $this->db->from('contents');

        if(!empty($title)) {
            $this->db->where('title', $title);
        }

        $this->db->where('type', $type);

        $rlt = $this->db->count_all_results();

        if($rlt) {
            return true;
        }else {
            return false;
        }
    }

}