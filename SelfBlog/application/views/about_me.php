<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</header>
<article class="mod-post mod-post__type-post">
    <header>
        <h2><?php echo $article['title'];?></h2>
    </header>
    <div class="mod-post__entry wzulli">
        <?php echo $article['content']; ?>
    </div>
    <div class="mod-post__meta">
        <div>
            <div>
                — 于 <time datetime=<?php echo $article['posttime'];?>><?php echo $article['posttime'];?></time>
            </div>
        </div>
    </div>
    <div>
    <a target="_blank" href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=WSoyIDwwNz82GSgodzo2NA" style="text-decoration:none;"><img src="http://rescdn.qqmail.com/zh_CN/htmledition/images/function/qm_open/ico_mailme_02.png"/></a>
    </div>
</article>

