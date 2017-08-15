<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</header>
<div id="divcloud" align="center">
    <?php foreach ($tags as $tag):?>
        <a href="http://www.skyeinfo.com/arttag.html/<?php echo $tag['mid']?>" style="color: <?php echo $colors[mt_rand(0, 6)];?>"><?php echo $tag['name'].'-'.$tag['postcount'];?></a>
    <?php endforeach;?>
</div>