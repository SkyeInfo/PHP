<?php
/**
 * 链式队列
 * @author skyeinfo@qq.com
 * @lastModifyTime 2018/11/8
 * @lastModify skyeinfo@qq.com
 */
namespace Queue;

use LinkedList\SingleLinkedListNode;

class QueueOnLinkedList
{
    public $head;
    public $tail;
    public $length;

    public function __construct() {
        $this->head = new SingleLinkedListNode();
        $this->tail = $this->head;

        $this->length = 0;
    }

    /**
     * 入队
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/8
     * @lastModify skyeinfo@qq.com
     * @param $data
     */
    public function enqueue($data) {
        $newNode = new SingleLinkedListNode();
        $newNode->data = $data;

        $this->tail->next = $newNode;
        $this->tail = $newNode;

        $this->length++;
    }

    /**
     * 出栈
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/8
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function dequeue() {
        if (0 == $this->length) {
            return false;
        }

        $node = $this->head->next;
        $this->head->next = $this->head->next->next;

        $this->length--;

        return $node->data;
    }

    /**
     * 获取队列长度
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/8
     * @lastModify skyeinfo@qq.com
     * @return int
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * 打印队列
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/8
     * @lastModify skyeinfo@qq.com
     */
    public function printQueue() {
        if (0 == $this->length) {
            echo 'empty queue' . PHP_EOL;
            return;
        }

        $curNode = $this->head;
        while ($curNode->next != null) {
            echo $curNode->next->data . ' -> ';

            $curNode = $curNode->next;
        }

        echo 'NULL' . PHP_EOL;
    }
}