<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</header>
<article class="mod-archive">
    <div class="mod-archive__item">
        <?php $year = 0;?>
        <?php foreach ($articles as $item): ?>
            <?php date_default_timezone_set('PRC');?>
            <?php
               $now_year=date('Y',strtotime($item['posttime']));
               if($year !== $now_year) {
                   echo '<div id='.date('Y',strtotime($item['posttime'])).' class="mod-archive__year">'.date('Y',strtotime($item['posttime'])).'</div>';
               }
            $year = $now_year;
            ?>
            <ul class="mod-archive__list">
                <li>
                    <time class="mod-archive__time" datetime=<?php echo date(date('Y-m-d',strtotime($item['posttime'])));?>><?php echo date(date('Y-m-d',strtotime($item['posttime'])));?></time>
                    <span>—</span>
                    <a href="<?php echo site_url('archive/'.$item['cid']);?>" title="<?php echo $item['title'];?>"><?php echo $item['title'];?></a>
                </li>
            </ul>
        <?php endforeach;?>
        <?php echo $pages;?>
    </div>
    <div align="center">
        <hr noshade="noshade">
        <p>已经到底啦~</p>
    </div>
</article>
