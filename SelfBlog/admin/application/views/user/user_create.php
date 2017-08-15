<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view("header") ?>
<?php $this->load->view("sidebar") ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <?php echo form_open('user/addUser')?>
        <ul class="list-group">
            <li class="list-group-item"><?php echo form_label('用户名:');?>
                <?php echo form_error('username'); ?>
                <?php echo form_input(array('name'=>'username','class'=>'form-control','value' => set_value('username'))); ?>
            </li>
            <li class="list-group-item"><?php echo form_label('邮箱地址:');?>
                <?php echo form_error('mail');?>
                <?php echo form_input(array('name'=>'mail','class'=>'form-control','value' => set_value('mail')));?>
            </li>
            <li class="list-group-item"><?php echo form_label('昵称:');?>
                <?php echo form_error('uname'); ?>
                <?php echo form_input(array('name'=>'uname','class'=>'form-control','value' => set_value('uname')));?>
            </li>
            <li class="list-group-item"><?php echo form_label('密码:');?>
                <?php echo form_error('pwd'); ?>
                <?php echo form_password(array('name'=>'pwd','class'=>'form-control'));?>
            </li>
            <li class="list-group-item"><?php echo form_label('密码确认:');?>
                <?php echo form_error('pwd-ack'); ?>
                <?php echo form_password(array('name'=>'pwd-ack','class'=>'form-control'));?>
            </li>
            <li class="list-group-item"><?php echo form_label('备注:');?>
                <?php echo form_error('log'); ?>
                <?php echo form_input(array('name'=>'log','class'=>'form-control','value' => set_value('log')));?>
            </li>
            <li  class="list-group-item" style="text-align:center">
                <input class="btn btn-primary" type="submit" name="submit" value="保存" id="submit">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input class="btn btn-primary" type="reset" name="reset" value="重置" id="reset">
            </li>
        </ul>
        <?php echo form_close();?>
    </div>
<?php $this->load->view("footer") ?>