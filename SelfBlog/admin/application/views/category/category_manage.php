<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view("header") ?>
<?php $this->load->view("sidebar") ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h3>
            分类目录
            <?php echo anchor('category/createCate', '新增分类', array('title'=>'新增分类','class'=>"btn btn-primary btn-xs",'role'=>"button")); ?>
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
                <th>分类ID</th>
                <th>名称</th>
                <th>父分类ID</th>
                <th>缩略名</th>
                <th>描述</th>
                <th>文章数</th>
                <th>添加时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($cates as $item): ?>
                <tr>
                    <td><input type="checkbox" <?php if ($item['pmid'] != 0) :?><?php echo 'name="checklist"'?><?php endif;?> id="<?php echo 'checkbox'.$item['mid'];?>" value="<?php echo $item['mid']?>" <?php if ($item['pmid'] == 0) :?><?php echo 'disabled'?><?php endif;?>/></td>
                    <td><?php echo $item['mid'];?></td>
                    <td><?php echo $item['name'];?></td>
                    <td><?php echo $item['pmid'];?></td>
                    <td><?php echo $item['slug'];?></td>
                    <td><?php echo $item['description'];?></td>
                    <td><a href="http://www.skyeinfo.com/arttag.html/<?php echo $item['mid']?>" target="_blank"><span class="badge"><?php echo $item['postcount'];?></span></a></td>
                    <td><?php echo $item['addtime'];?></td>
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
            if (val.length == 0){
                alert('请选择需要删除的分类！');
                return false;
            }
            if(!confirm("确定删除？")){
                return false;
            }
            $.post("<?php echo site_url('Category/delCate')?>",{ids:val},function(data){success_response(data);})
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