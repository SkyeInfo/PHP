<?php
/**
 * 用(数组)栈表示表达式运算过程
 * @author yangshengkai@chuchujie.com
 * @lastModifyTime 2018/11/7
 * @lastModify yangshengkai@chuchujie.com
 */

$t = new Expression();
$t->handleExpression('-1+2-(1+2*3)');
class Expression
{
    public function __construct() { }

    public function handleExpression($str) {
        $str = str_replace(' ', '', $str);

        $arr = preg_split('/([\+\-\*\/\(\)])/', $str, 0, PREG_SPLIT_DELIM_CAPTURE |  PREG_SPLIT_NO_EMPTY);
        $count = count($arr);

        $numStack = $operatorStack = [];
        $operatorStack[] = null;
        for ($i = 0; $i < $count; $i++) {
            var_dump($numStack, $operatorStack);
            try {
                if (ord($arr[$i]) >= 48 && ord($arr[$i] <= 57)) {   //ASCII 判断数字，is_numeric
                    array_push($numStack, $arr[$i]);
                    continue;
                }

                switch ($arr[$i]) {
                    case '+':
                    case '-':
                        $arrLastIndex = count($operatorStack) - 1;
                        while ($operatorStack[$arrLastIndex] == '*' || $operatorStack[$arrLastIndex] == '/' || $operatorStack[$arrLastIndex] == '-') {
                            $this->compute($numStack, $operatorStack);
                            $arrLastIndex--;
                        }
                        array_push($operatorStack, $arr[$i]);
                        break;
                    case '*':
                        $arrLastIndex = count($operatorStack) - 1;
                        while ($operatorStack[$arrLastIndex] == '/') {
                            $this->compute($numStack, $operatorStack);
                            $arrLastIndex--;
                        }
                        array_push($operatorStack, $arr[$i]);
                        break;
                    case '/':
                    case '(':
                        array_push($operatorStack, $arr[$i]);
                        break;
                    case ')':
                        $arrLastIndex = count($operatorStack) - 1;
                        while ($operatorStack[$arrLastIndex] !== '(') {
                            $this->compute($numStack, $operatorStack);
                            $arrLastIndex--;
                        }
                        array_pop($operatorStack);
                        break;
                    default:
                        throw new \Exception("不支持的运算符", 2);
                        break;
                }
            }catch (Exception $e) {
                die($e->getCode() . '--->' . $e->getMessage());
            }
        }

        $arrLen = count($operatorStack);
        while ($operatorStack[$arrLen-1] !== NULL){
            $this->compute($numStack, $operatorStack);
            $arrLen--;
        }
        echo array_pop($numStack);
    }

    public function compute(&$numStack, &$operatorStack) {
        $num = array_pop($numStack);
        switch (array_pop($operatorStack)) {
            case '*':
                array_push($numStack, array_pop($numStack) * $num);
                break;
            case '/':
                array_push($numStack, array_pop($numStack) / $num);
                break;
            case '+':
                array_push($numStack, array_pop($numStack) + $num);
                break;
            case '-':
                array_push($numStack, array_pop($numStack) - $num);
                break;
            case '(':
                throw new \Exception('括号不匹配', 1);
                break;
            default:
                break;
        }
    }
}
