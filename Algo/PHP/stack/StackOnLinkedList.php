<?php
/**
 * 基于单链表实现栈
 * @author skyeinfo@qq.com
 * @lastModifyTime 2018/11/7
 * @lastModify skyeinfo@qq.com
 */
namespace Stack;

use LinkedList\SingleLinkedListNode;

class StackOnLinkedList
{
    public $head;

    public $length;

    public function __construct() {
        $this->head = new SingleLinkedListNode();
        $this->length = 0;
    }

    /**
     * 弹出一个数据
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/7
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function pop() {
        if (0 == $this->length) {
            return false;
        }

        $data = $this->head->next->data;

        $this->head->next = $this->head->next->next;
        $this->length--;

        return $data;
    }

    /**
     * push一个数据
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/7
     * @lastModify skyeinfo@qq.com
     * @param $data
     * @return bool
     */
    public function push($data) {
        return $this->pushData($data);
    }

    public function pushData($data) {
        $node = new SingleLinkedListNode($data);

        if (null == $node) {
            return false;
        }

        $node->next = $this->head->next;
        $this->head->next = $node;
        $this->length++;

        return true;
    }

    /**
     * 打印栈
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/7
     * @lastModify skyeinfo@qq.com
     */
    public function printStack() {
        if (0 == $this->length) {
            echo 'empty stack' . PHP_EOL;
            return;
        }

        $curNode = $this->head;
        while ($curNode->next != null) {
            echo $curNode->next->data . ' -> ';

            $curNode = $curNode->next;
        }
        echo 'NULL' . PHP_EOL;
    }

    /**
     * 获取长度
     * @authorskyeinfo@qq.com
     * @lastModifyTime 2018/11/7
     * @lastModify skyeinfo@qq.com
     * @return int
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * 判空
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/7
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function isEmpty() {
        return $this->length == 0 ? true : false;
    }

}

