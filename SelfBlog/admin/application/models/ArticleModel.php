<?php
/**
 * 文章模型
 * @author yangshengkai
 * @time 2017/05/07
 */

class ArticleModel extends CI_Model
{
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * 插入文章
     * @param $data
     * @return mixed
     */
    public function insertArticle($data)
    {
        //向article表中插入数据
        if($data != null) {
            $this->db->insert('contents',$data);
            return $this->db->affected_rows();
        }
    }

    /**
     * 删除文章
     */
    public function deleteArticle($cids)
    {
        $flag = true;
        $this->db->trans_begin();
        foreach ($cids as $k => $cid){
            $mid = $this->getMidByCid($cid);
            if ($mid == 0){
                $this->db->trans_rollback();
                return false;
            }
            $mids = $this->getAllFatherMid($mid);
            foreach ($mids as $key => $v){
                $this->db->set('postcount', 'postcount-1', false);
                $this->db->where('mid', $v);
                $result = $this->db->update('metas');
                if (!$result){
                    $this->db->trans_rollback();
                    return false;
                }
            }
            $this->db->where('cid', $cid);
            $r = $this->db->delete('contents');
            if (!$r){
                $this->db->trans_rollback();
                return false;
            }
            $flag = $flag && $r;
        }
        $this->db->trans_commit();
        return $flag;
    }

    /**
     * 获取分类ID
     * @param $id
     * @return int
     */
    protected function getMidByCid($id)
    {
        $this->db->select('mid');
        $this->db->from('contents');
        $this->db->where('cid', $id);
        $rlt = $this->db->get()->row_array();
        if (!empty($rlt)){
            return $rlt['mid'];
        }
        return 0;
    }

    /**
     * 递归获取所有的父分类
     * @return array
     */
    public function getAllFatherMid($mid)
    {
        $fatherMid[] = intval($mid);
        $single_fatherMid = $this->getFatherMid($mid);

        if (!empty($single_fatherMid)) {
            do{
                $fatherMid[] = intval($single_fatherMid['pmid']);

                $single_fatherMid = $this->getFatherMid(intval($single_fatherMid['pmid']));

            }while (!empty($single_fatherMid));
        }
        return $fatherMid;
    }
    /**
     * 获取子分类下的父分类
     * @return array
     */
    public function getFatherMid($mid)
    {
        $this->db->select('pmid');
        $this->db->from('metas');
        $this->db->where('mid', $mid);

        $rlt = $this->db->get()->row_array();

        return $rlt;
    }

    /**
     * 获取文章
     * @param array $limit
     * @return mixed
     */
    public function getArticle($limit = array('num' => false, 'offset'=> false))
    {
        $this->db->select('contents.title, contents.cid, contents.posttime, contents.mid, contents.author, metas.name');
        $this->db->from('contents');
        $this->db->join('metas', 'metas.mid = contents.mid', 'left');
        $this->db->where('contents.type','post');
        $this->db->order_by('contents.posttime', 'DESC');
        $this->db->limit($limit['num'], $limit['offset']);
        $query = $this->db->get()->result_array();
        return $query;
    }

    /**
     * 获取单个文章
     * @param $cid
     * @return mixed
     */
    public function getOneArticle($cid)
    {
        $this->db->select('*');
        $this->db->from('contents');
        $this->db->where(array('cid' => $cid));
        $query = $this->db->get()->row_array();

        return $query;
    }

    /**
     * 获取文章数量
     * @param string $type
     * @param bool $title
     * @param bool $cid
     * @return mixed
     */
    public function getArticlesCount($type ='post', $title = false, $cid = false)
    {
        $this->db->where('type', $type);
        if($title) {
            $this->db->where('title', $title);
        }
        if($cid) {
            $this->db->where('cid', $cid);
        }
        $this->db->from('contents');
        return $this->db->count_all_results();
    }

    /**
     * 更新文章
     * @param bool $cid
     * @param array $article
     * @return bool
     */
    public function updateArticle($cid = false, $article = array())
    {
        if(is_numeric($cid)) {
            $old_mid = $this->getMidByCid($cid);
            $new_mid = $article['mid'];
            $rlt1 = true;
            $rlt2 = true;
            if ($old_mid != $new_mid){
                $old_mids = $this->getAllFatherMid($old_mid);
                foreach ($old_mids as $key => $v){
                    $this->db->set('postcount', 'postcount-1', false);
                    $this->db->where('mid', $v);
                    $rlt1 = $this->db->update('metas');
                }

                $new_mids = $this->getAllFatherMid($new_mid);
                foreach ($new_mids as $k => $val){
                    $this->db->set('postcount', 'postcount+1', false);
                    $this->db->where('mid', $val);
                    $rlt2 = $this->db->update('metas');
                }
            }
            $this->db->where('cid', $cid);
            $rlt = $this->db->update('contents', $article);

            return $rlt&&$rlt1&&$rlt2;
        }
        return false;
    }

    /**
     * 更新“关于”
     * @param bool $cid
     * @param array $article
     * @return bool
     */
    public function updateAbout($cid = false, $article = array())
    {
        if(is_numeric($cid)) {
            $this->db->where('cid', (int)$cid);
            $rlt = $this->db->update('contents', $article);

            return $rlt;
        }
        return false;
    }

    /**
     * 根据类型获取文章
     * @param string $type
     * @param bool $title
     * @return mixed
     */
    public function getTypeArticles($type ='post', $title = false)
    {
        if($title) {
            $this->db->where('title', $title);
        }
        $this->db->where('type', $type);
        $this->db->from('contents');
        $query = $this->db->get()->result_array();
        return $query;
    }

}