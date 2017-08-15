<?php
/**
 * 分类管理模型
 * @author yangshengkai
 * @time 2017/04/11
 */

class CategoryModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取全部分类
     * @return mixed
     */
    public function getAllCates()
    {
        $this->db->select('mid, pmid, name');
        $this->db->from('metas');
        $this->db->where('mid !=', 1);
        $this->db->order_by('mid', 'ASC');

        $rlt = $this->db->get()->result_array();

        return $rlt;
    }

    /**
     * 获取部分分类
     * @return mixed
     */
    public function getCates()
    {
        $this->db->select('*');
        $this->db->from('metas');
        $this->db->order_by('mid', 'ASC');
        $rlt = $this->db->get()->result_array();

        return $rlt;
    }

    /**
     * 添加分类
     * @param $data
     * @return mixed
     */
    public function insertCategory($data)
    {
        if($data !== null) {
            $this->db->insert('metas', $data);

            return $this->db->affected_rows();
        }
    }

    /**
     * 获取指定文章的分类
     */
    public function getArticleCategory($cid = false)
    {
        if($cid) {
            $this->db->select('mid');
            $this->db->from('contents');
            $this->db->where('cid', $cid);
            $mid = $this->db->get()->row_array();

            $pmids = $this->getAllFatherMid($mid['mid']);

            $this->db->select('mid, pmid, name');
            $this->db->from('metas');
            $this->db->where_in('mid', $pmids);

            $rlt = $this->db->get()->result_array();
            return $rlt;
        } else {

            $rlt = $this->getAllCates();
            return $rlt;
        }
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
     * @return arr
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
     * where_in获取所有文章发布数量
     */
    public function getPostNum($mids)
    {
        $this->db->select('postcount, mid');
        $this->db->from('metas');
        $this->db->where_in('mid', $mids);
        $rlt = $this->db->get()->result_array();

        return $rlt;
    }

    /**
     * 批量更新文章发布数
     */
    public function updatePostNum($postNum)
    {
        return $this->db->update_batch('metas', $postNum, 'mid');
    }

    /**
     * 删除分类
     */
    public function delCate($mids)
    {
        $flag = true;
        if (is_array($mids) && !empty($mids)){
            foreach ($mids as $k => $v){
                $pmid = $this->getFatherMid($v);
                $data = array(
                    'mid' => $pmid['pmid']
                );
                $this->db->where('mid', (int)$v);
                $rlt = $this->db->update('contents', $data);

                $data1 = array(
                    'pmid' => $pmid['pmid']
                );
                $this->db->where('pmid', (int)$v);
                $rlt1 = $this->db->update('metas', $data1);

                if ($rlt && $rlt1){
                    $this->db->where('mid', (int)$v);
                    $result = $this->db->delete('metas');
                    $flag = $result && $flag;
                }
            }
            return $flag;
        }else {
            return false;
        }
    }

}