<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view("header") ?>
<?php $this->load->view("sidebar") ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h3>
            “关于”列表
        </h3>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>标题</th>
                <th>作者</th>
                <th>日期</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pages as $item):?>
                <tr>
                    <td><?php echo $item['cid'];?></td>
                    <td>
                        <strong><?php echo anchor( 'about/editPage/'.$item['cid'], $item['title'], array('title' => '编辑'));?></strong>
                        <div style="padding: 2px 0px 0px;font-size:13px">
                            <span><a href="http://www.skyeinfo.com/aboutMe.html" title="查看" target="_blank">查看</a></span>
                        </div>
                    </td>
                    <td><?php echo $item['author'];?></td>
                    <td><?php echo $item['posttime'];?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php $this->load->view("footer") ?>