<?php
/**
 * 单链表
 * @author skyeinfo@qq.com
 * @lastModifyTime 2018/10/23
 * @lastModify skyeinfo@qq.com
 */
namespace Algo\PHP\linkedList;

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
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/10/23
     * @lastModify skyeinfo@qq.com
     * @return 单链表长度|int
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * 在头结点后插入一条数据
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/10/23
     * @lastModify skyeinfo@qq.com
     * @param $data
     * @return mixed
     */
    public function insert($data) {
        return $this->insertDataAfter($this->head, $data);
    }

    /**
     * 在某个节点后插入新的数据（只接受值）
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/10/25
     * @lastModify skyeinfo@qq.com
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
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/10/25
     * @lastModify skyeinfo@qq.com
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
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/10/25
     * @lastModify skyeinfo@qq.com
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

    /**
     * 通过索引获取节点
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/5
     * @lastModify skyeinfo@qq.com
     * @param $index
     * @return null
     */
    public function getNodeByIndex($index) {
        if ($index >= $this->length) {
            return null;
        }

        $current = $this->head->next;
        for ($i = 0; $i < $index; $i++) {
            $current = $current->next;
        }

        return $current;
    }

    /**
     * 打印链表
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/5
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function printList() {
        if (null == $this->head->next) {
            return false;
        }

        $currNode = $this->head;

        $listLength = $this->getLength();   //防止链表带环

        while ($currNode->next != null && $listLength--) {
            echo $currNode->next->data . ' -> ';

            $currNode = $currNode->next;
        }

        echo 'NULL' . PHP_EOL;

        return true;
    }

    /**
     * 打印单链表
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/5
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function printListSimple() {
        if (null == $this->head->next) {
            return false;
        }

        $currNode = $this->head;
        while ($currNode->next != null) {
            echo $currNode->next->data . ' -> ';

            $currNode = $currNode->next;
        }
        echo 'NULL' . PHP_EOL;

        return true;
    }

    /**
     * 在某节点前插入一个数据
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/5
     * @lastModify skyeinfo@qq.com
     * @param SingleLinkedListNode $originNode
     * @param $data
     * @return SingleLinkedListNode|bool
     */
    public function insertDataBefore(SingleLinkedListNode $originNode, $data) {
        if (null == $originNode) {
            return false;
        }

        $preNode = $this->getPreNode($originNode);

        $rlt = $this->insertDataAfter($preNode, $data);

        return $rlt;
    }

    /**
     * 在某节点后添加一个新节点
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/5
     * @lastModify skyeinfo@qq.com
     * @param SingleLinkedListNode $originNode
     * @param SingleLinkedListNode $node
     * @return SingleLinkedListNode|bool
     */
    public function insertNodeAfter(SingleLinkedListNode $originNode, SingleLinkedListNode $node) {
        if (null == $originNode) {
            return false;
        }
        $node->next = $originNode->next;
        $originNode->next = $node;

        $this->length++;

        return $node;
    }
}
