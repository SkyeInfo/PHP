<?
/**
 * @param $url
 * @return bool|string
 */
function downloadImage($url) {
    $urlExp = explode('?', $url);
    $url = $urlExp[0];

    $ext = strtolower(strrchr($url, "."));
    if ($ext != ".png" && $ext != ".jpg" && $ext != ".jpeg") return false;

    $path = __DIR__ . '/imageTemp/' . date("Ymd") . '/';   //本地路径

    if (!is_dir($path)) {
        mkdir(iconv("UTF-8", "GBK", $path), 0755, true);
    }

    $imgOriginName = basename($url);   //获取图片的原始名字，包含扩展名
    $filename = $path . date("YmdHis") . $imgOriginName;   //在原始文件名字前加上时间避免重复
    
    ob_start();    //打开输出
    readfile($url);//输出图片文件
    $img = ob_get_contents();//得到浏览器输出
    ob_end_clean();   //清除输出并关闭
    $fp2 = @fopen($filename, "a");   //打开文件
    fwrite($fp2, $img);   //写入文件
    fclose($fp2);    //关闭文件柄
    
    return $filename;//返回新的文件路径，包含文件名
}

?>