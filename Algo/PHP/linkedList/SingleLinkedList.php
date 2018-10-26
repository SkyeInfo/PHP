<?php
/**
 * 单链表
 * @author yangshengkai
 * @lastModifyTime 2018/10/23
 * @lastModify yangshengkai
 */
namespace Algo\PHP\linkedList;

use Algo\PHP\linkedList\SingleLinkedListNode;

class SingleLinkedList{

    /** @var 头结点（哨兵节点） */
    public $head;

    /** @var 单链表长度 */
    private $length;

    public function __construct($head = null) {
        if (null == $head) {
            $this->head = new SingleLinkedListNode();
        } else {
            $this->head = $head;
        }

        //初始化长度为0
        $this->length = 0;
    }

    /**
     * 获取链表长度
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime 2018/10/23
     * @lastModify yangshengkai@chuchujie.com
     * @return 单链表长度|int
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * 在头结点后插入一条数据
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime 2018/10/23
     * @lastModify yangshengkai@chuchujie.com
     * @param $data
     * @return mixed
     */
    public function insert($data) {
        return $this->insertDataAfter($this->head, $data);
    }

    /**
     * 在某个节点后插入新的节点（只接受值）
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime 2018/10/25
     * @lastModify yangshengkai@chuchujie.com
     * @param \Algo\PHP\linkedList\SingleLinkedListNode $originNode
     * @param $data
     * @return \Algo\PHP\linkedList\SingleLinkedListNode|bool
     */
    public function insertDataAfter(SingleLinkedListNode $originNode, $data) {
        if (null == $originNode) {
            return false;
        }

        //新创建一个节点
        $newNode = new SingleLinkedListNode();

        $newNode->data = $data;

        $newNode->next = $originNode->next;
        $originNode->next = $newNode;

        $this->length++;

        return $newNode;
    }

    /**
     * 删除某一个节点
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime 2018/10/25
     * @lastModify yangshengkai@chuchujie.com
     * @param \Algo\PHP\linkedList\SingleLinkedListNode $node
     * @return bool
     */
    public function delete(SingleLinkedListNode $node) {
        if (null == $node) {
            return false;
        }

        $preNode = $this->getPreNode($node);

        $preNode->next == $node->next;
        unset($node);

        $this->length--;

        return true;
    }

    /**
     * 获取某节点的前一个节点
     * @author yangshengkai@chuchujie.com
     * @lastModifyTime 2018/10/25
     * @lastModify yangshengkai@chuchujie.com
     * @param \Algo\PHP\linkedList\SingleLinkedListNode $node
     * @return 头结点（哨兵节点）|bool|null
     */
    public function getPreNode(SingleLinkedListNode $node) {
        if (null == $node) {
            return false;
        }

        //从头部节点开始遍历
        $curNode = $this->head;
        $preNode = $this->head;

        while ($curNode !== $node && $curNode != null) {
            $preNode = $curNode;
            $curNode = $curNode->next;
        }

        return $preNode;
    }

    public function getNodeByIndex($index) {
        if ($index >= $this->length) {
            return null;
        }
    }


}
