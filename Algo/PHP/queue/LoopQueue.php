<?php
/**
 * 用数组实现循环队列
 * @author skyeinfo@qq.com
 * @lastModifyTime 2018/11/8
 * @lastModify skyeinfo@qq.com
 */
class LoopQueue
{
    private $maxSize;
    private $data = [];
    private $head = 0;
    private $tail = 0;   //index

    /**
     * 初始化时设置大小，由于最后位置不存放数据，实际大小即为++size
     * LoopQueue constructor.
     * @param int $size
     */
    public function __construct($size = 10) {
        $this->maxSize = ++$size;
    }

    /**
     * 判满条件 ($this->tail + 1) % $this->maxSize == $this->head
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/8
     * @lastModify skyeinfo@qq.com
     * @param $data
     * @return int
     */
    public function enqueue($data) {
        if (($this->tail + 1) % $this->maxSize == $this->head) {
            return -1;
        }

        $this->data[$this->tail] = $data;
        $this->tail = (++$this->tail) % $this->maxSize;
    }

    /**
     * 出队列
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/8
     * @lastModify skyeinfo@qq.com
     * @return mixed|null
     */
    public function dequeue() {
        if ($this->head == $this->tail) {
            return null;
        }

        $data = $this->data[$this->head];
        unset($this->data[$this->head]);
        $this->head = (++$this->head) % $this->maxSize;

        return $data;
    }

    /**
     * 获取长度
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/11/8
     * @lastModify skyeinfo@qq.com
     * @return int
     */
    public function getLength() {
        return ($this->maxSize + $this->head - $this->tail) % $this->maxSize;
    }
}

$queue = new LoopQueue(4);
$queue->enqueue(1);
$queue->enqueue(2);
$queue->enqueue(3);
$queue->enqueue(4);
var_dump($queue);

$queue->dequeue();
$queue->dequeue();

var_dump($queue);
$queue->enqueue(6);
$queue->enqueue(7);
var_dump($queue);

$queue->dequeue();
$queue->dequeue();
$queue->dequeue();
var_dump($queue);

