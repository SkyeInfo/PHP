<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view("header") ?>
<?php $this->load->view("sidebar") ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <?php echo form_open('links/updateLink/'.$link['id'])?>
        <ul class="list-group">
            <li class="list-group-item"><?php echo form_label('作者:');?>
                <?php echo form_error('bloger'); ?>
                <?php echo form_input(array('name'=>'bloger','class'=>'form-control','value' => $link['bloger'])); ?>
            </li>
            <li class="list-group-item"><?php echo form_label('博客链接:');?>
                <?php echo form_error('url');?>
                <?php echo form_input(array('name'=>'url','class'=>'form-control','value' => $link['url']));?>
            </li>
            <li class="list-group-item"><?php echo form_label('描述:');?>
                <?php echo form_error('description'); ?>
                <?php echo form_input(array('name'=>'description','class'=>'form-control','value' => $link['description']));?>
            </li>
            <li class="list-group-item"><?php echo form_label('类型:');?>
                <?php echo form_error('type'); ?>
                <label class="radio-inline">
                    <input type="radio" name="type" id="1" value="tech" <?php if ($link['type'] == 'tech'):?><?php echo 'checked="true"';?><?php endif;?>>技术类
                </label>
                <label class="radio-inline">
                    <input type="radio" name="type" id="2" value="study" <?php if ($link['type'] == 'study'):?><?php echo 'checked="true"';?><?php endif;?>>学习类
                </label>
            </li>
            <li  class="list-group-item" style="text-align:center">
                <input class="btn btn-primary" type="submit" name="submit" value="更新" id="submit">
            </li>
        </ul>
        <?php echo form_close();?>
    </div>
<?php $this->load->view("footer") ?>