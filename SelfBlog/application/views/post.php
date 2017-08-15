<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</header>
<article class="mod-post mod-post__type-post">
    <header>
        <h1 class="mod-post__title"><?php echo $title;?></h1>
    </header>
    <div class="mod-post__entry wzulli">
        <?php echo $content; ?>
    </div>
    <div class="mod-post__meta">
        <div>
            <div>
                — 于 <time datetime=<?php echo $posttime;?>><?php echo $posttime;?></time>
            </div>
        </div>
    </div>
</article>