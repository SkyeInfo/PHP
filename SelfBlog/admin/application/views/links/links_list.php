<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view("header") ?>
<?php $this->load->view("sidebar") ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h3>
            链接列表
            <?php echo anchor('links/create', '新增链接', array('title'=>'新增链接','class'=>"btn btn-primary btn-xs",'role'=>"button")); ?>
        </h3>
        <form method="get">
            <div name="options" style="margin: 3px;float: left;">
                <div class="dropdown">
                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                        选中项
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-xs" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation"><a role="del" tabindex="-1" href="javascript:void(0)" id="delSelected">删除</a></li>
                    </ul>
                </div>
            </div>
            <div name="search" style="float: right">
                <div name="options" style="margin: 3px;float: left;">
                    <div class="dropdown">
                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                            所有分类
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-xs" role="menu" aria-labelledby="dropdownMenu1">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">默认分类</a></li>
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-xs" style="margin: 3px;">筛选</button>
            </div>
        </form>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th><input type="checkbox" name="checkAll" id="checkAll"/></th>
                <th>ID</th>
                <th>作者</th>
                <th>链接</th>
                <th>描述</th>
                <th>类型</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($links as $item):?>
                <tr>
                    <td><input type="checkbox" name="checklist" id="<?php echo 'checkbox'.$item['id']?>" value="<?php echo $item['id']?>" /></td>
                    <td><?php echo $item['id'];?></td>
                    <td>
                        <strong><?php echo anchor( 'links/editLink/'.$item['id'], $item['bloger'], array('title' => '编辑'));?></strong>
                    </td>
                    <td><span><a href="<?php echo $item['url'];?>" target="_blank" title="访问"><?php echo $item['url'];?></a></span></td>
                    <td><?php echo $item['description'];?></td>
                    <?php if ($item['type'] == 'tech'):?>
                        <td><?php echo "技术类";?></td>
                    <?php elseif ($item['type'] == 'study'):?>
                        <td><?php echo "学习类";?></td>
                    <?php else:?>
                        <td><?php echo "未知类";?></td>
                    <?php endif;?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        $("#checkAll").click(function(){
                if(this.checked){
                    $("input[name='checklist']").each(function(){
                        this.checked=true;
                    });
                }else{
                    $("input[name='checklist']").each(function(){
                        this.checked=false;
                    });
                }
            }
        );

        $("#delSelected").click(function(){
            var val = new Array();
            $("input[name=checklist]").each(function(){
                    if(this.checked)
                    {
                        val.push(this.value);
                    }
                }
            );
            if (val.length === 0){
                alert('请选择需要删除的链接！');
                return false;
            }
            if(!confirm("确定删除？")){
                return false;
            }
            $.post("<?php echo site_url('links/delLink')?>",{ids:val},function(data){success_response(data);})
        });
        function success_response(response) {
            var rlt = eval('(' + response + ')');
            if(rlt.errno !== 0){
                alert(rlt.msg);
                return false;
            }
            alert("删除成功");
            window.location.reload(true);
        }
    </script>
<?php $this->load->view("footer") ?>