<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view("header") ?>
<?php $this->load->view("sidebar") ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h3>
            用户列表
            <?php echo anchor('user/create', '新增用户', array('title'=>'新增用户','class'=>"btn btn-primary btn-xs",'role'=>"button")); ?>
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
        </form>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th><input type="checkbox" name="checkAll" id="checkAll"/></th>
                <th>用户ID</th>
                <th>用户名</th>
                <th>昵称</th>
                <th>电子邮件</th>
                <th>创建时间</th>
                <th>备注</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $item):?>
                <tr>
                    <?php if ($item['uid'] != 1) :?>
                    <td><input type="checkbox" name="checklist" id="<?php echo 'checkbox'.$item['uid']?>" value="<?php echo $item['uid']?>" /></td>
                    <?php else:?>
                    <td><input type="checkbox" name="checklist" id="<?php echo 'checkbox'.$item['uid']?>" value="<?php echo $item['uid']?>" disabled="disabled"/></td>
                    <?php endif;?>

                    <td><?php echo $item['uid'];?></td>
                    <td>
                        <strong><?php echo anchor( 'user/editUser/'.$item['uid'], $item['username'], array('title' => '编辑'));?></strong>
                    </td>
                    <td><?php echo $item['uname'];?></td>
                    <td><?php echo $item['mail'];?></td>
                    <td><?php echo $item['createtime'];?></td>
                    <td><?php echo $item['log'];?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        $("#checkAll").click(function(){
                if(this.checked){
                    $("input[name='checklist']").each(function(){
                        if(this.value != 1){
                            this.checked=true;
                        }
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
            $("input[name = checklist]").each(function(){
                    if(this.checked)
                    {
                        val.push(this.value);
                    }
                }
            );
            if (val.length == 0){
                alert("请选择要删除的数据！");
            }else {
                var confirmMsg = "确定要删除吗？";
                if (confirm(confirmMsg) == true){

                    $.post("<?php echo site_url('user/delUser')?>",{ids:val},function(data){
                        var obj = eval ("(" + data + ")");
                        if (obj.errno == 0){
                            alert("删除成功！");
                            window.location.reload();
                        }else if (obj.errno == 1){
                            alert("删除失败，请重试！");
                            window.location.reload();
                        }else if (obj.errno == 2){
                            alert("请选择要删除的数据！");
                            window.location.reload();
                        }else{
                            alert("未知错误！");
                        }
                    })
                }else{
                    window.location.reload();
                }
            }
        });
    </script>
<?php $this->load->view("footer") ?>