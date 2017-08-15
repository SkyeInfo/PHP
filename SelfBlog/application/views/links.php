</header>
<div>
    <table>
        <tr>
            <h3><li>技术大佬的博客</li></h3>
        </tr>
        <tr></tr>
        <?php foreach ($linksinfo as $links): ?>
            <?php if ($links['type'] == 'tech'):?>
                <tr>
                    <li><a href="<?php echo $links['url']?>" target="_blank"><?php echo $links['bloger'];?></a>
                    </li>
                </tr>
            <?php endif;?>
        <?php endforeach;?>
    </table>
    <hr>
    <table>
        <tr>
            <h3><li>学习网站</li></h3>
        </tr>
        <tr></tr>
        <?php foreach ($linksinfo as $links): ?>
            <?php if ($links['type'] == 'study'):?>
                <tr>
                    <li><a href="<?php echo $links['url']?>" target="_blank"><?php echo $links['bloger'];?></a>
                    </li>
                </tr>
            <?php endif;?>
        <?php endforeach;?>
    </table>
    <?php echo $pages;?>
</div>
<div align="center">
    <hr noshade="noshade">
    <p>已经到底啦~</p>
</div>