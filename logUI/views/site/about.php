<?php

/* @var $this yii\web\View */

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1>搜索帮助</h1>
    <h2>字段</h2>
    <p>
        限定字段全文搜索：<code>field:value</code><br/>
        精确搜索：关键字加上双引号 <code>filed:"value"</code><br/>
        <code>http.code:404</code> 搜索http状态码为404的文档
    </p>
    <p>
        字段本身是否存在<br/>
        <code>_exists_:http</code>：返回结果中需要有http字段<br/>
        <code>_missing_:http</code>：不能含有http字段
    </p>

    <h2>通配符</h2>
    <p>
        <code>?</code> 匹配单个字符<br/>
        <code>*</code> 匹配0到多个字符<br/>
        <code>kiba?a</code>, <code>el*search</code><br/>
        <code>? *</code> 不能用作第一个字符，例如：<code>?text *text</code>
    </p>

    <h2>正则</h2>
    支持部分正则功能<br/>
    <code>mesg:/mes{2}ages?/</code>

    <h2>模糊搜索</h2>
    <code>~</code>:在一个单词后面加上<code>~</code>启用模糊搜索<br/>
    <code>first~</code> 也能匹配到 frist<br/>
    还可以指定需要多少相似度<br/>
    <code>cromm~0.3</code> 会匹配到 from 和 chrome<br/>
    数值范围0.0 ~ 1.0，默认0.5，越大越接近搜索的原始值

    <h2>近似搜索</h2>
    在短语后面加上<code>~</code><br/>
    <code>"select where"~3</code> 表示 select 和 where 中间隔着3个单词以内

    <h2>范围搜索</h2>
    数值和时间类型的字段可以对某一范围进行查询<br/>
    <code>length:[100 TO 200]</code><br/>
    <code>date:{"now-6h" TO "now"}</code><br/>
    [ ] 表示端点数值包含在范围内，{ } 表示端点数值不包含在范围内

    <h2>逻辑操作</h2>
    <code>AND</code><br/>
    <code>OR</code><br/>
    <br/>
    <code>+</code>：搜索结果中必须包含此项<br/>
    <code>-</code>：不能含有此项<br/>
    <code>+apache -jakarta test</code>：结果中必须存在apache，不能有jakarta，test可有可无

    <h2>分组</h2>
    <code>(jakarta OR apache) AND jakarta</code>

    <h2>字段分组</h2>
    <code>title:(+return +"pink panther")</code>

    <h2>转义特殊字符</h2>
    <code>+ - && || ! () {} [] ^" ~ * ? : \</code><br/>
    以上字符当作值搜索的时候需要用\转义
</div>
