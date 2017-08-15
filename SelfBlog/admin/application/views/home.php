<?php
/**
 * User: yangshengkai
 * Time: 2017/02/21
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script language="javascript">
    window.setInterval('showTime()',1000)
    function showTime()
    {
        var enabled = 0;
        today = new Date();
        var day;
        var date;
        if(today.getDay()==0) day = "星期日";
        if(today.getDay()==1) day = "星期一";
        if(today.getDay()==2) day = "星期二";
        if(today.getDay()==3) day = "星期三";
        if(today.getDay()==4) day = "星期四";
        if(today.getDay()==5) day = "星期五";
        if(today.getDay()==6) day = "星期六";
        function checkTime(i) {
            if (i<10){
                i = "0" + i;
            }
            return i;
        }
        date = (today.getFullYear()) + "年" + (checkTime(today.getMonth() + 1)) + "月" + checkTime(today.getDate()) + "日 " +
            day+" "+checkTime(today.getHours())+":"+checkTime(today.getMinutes())+":"+checkTime(today.getSeconds());
        document.getElementById("time").innerHTML = date;
    }
</script>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div onload="showTime()" style="text-align: right; font-size: 16px" id="time"></div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">技术大佬的博客</h3>
        </div>
        <div class="panel-body">
            <ul class="list-group">
                <?php foreach ($linksinfo as $links): ?>
                    <?php if ($links['type'] == 'tech'):?>
                        <li class="list-group-item list-group-item-info">
                            <a href="<?php echo $links['url']?>" target="_blank"><?php echo $links['bloger'];?></a>
                            <br>
                        <li class="list-group-item list-group-item-success">
                            <?php echo $links['description']?>
                        </li>
                        </li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">学习网站</h3>
        </div>
        <div class="panel-body">
            <ul class="list-group">
                <?php foreach ($linksinfo as $links): ?>
                    <?php if ($links['type'] == 'study'):?>
                        <li class="list-group-item list-group-item-info">
                            <a href="<?php echo $links['url']?>" target="_blank"><?php echo $links['bloger'];?></a>
                            <br>
                        <li class="list-group-item list-group-item-danger">
                            <?php echo $links['description']?>
                        </li>
                        </li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>





