<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view("header") ?>
<?php $this->load->view("sidebar") ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h3>
            新增分类
        </h3>
        <?php echo form_open('category/addCategory'); ?>
            <ul class="list-group">
                <li class="list-group-item"><?php echo form_label('分类名称:');?>
                    <?php echo form_error('name'); ?>
                    <?php echo form_input(array('name'=>'name','class'=>'form-control'));?>
                </li>
                <li class="list-group-item"><?php echo form_label('分类缩略名:');?>
                    <?php echo form_input(array('name'=>'slug','class'=>'form-control'));?>
                </li>
                <li class="list-group-item"><?php echo form_label('父级分类:');?>
                    <?php foreach ($categories as $item):?>
                        <label class="radio-inline">
                            <input type="radio" name="pmid" id="<?php echo 'cate'.$item['mid'] ?>" value="<?php echo $item['mid']?>" checked="true"> <?php echo $item['name']?>
                        </label>
                        <?php ?>
                    <?php endforeach;?>
                </li>
                <li class="list-group-item"><?php echo form_label('分类描述:');?>
                    <?php echo form_textarea(array('name'=>'description','class'=>'form-control'));?>
                </li>
                <li  class="list-group-item" style="text-align:center"><input class="btn btn-primary" type="submit" name="submit" value="添加分类">
                </li>
            </ul>
        <?php echo form_close();?>
<?php $this->load->view("footer") ?>