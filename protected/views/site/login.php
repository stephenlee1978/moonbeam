<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* stephen fix 2015-1-5  */
$this->pageTitle = '欢迎使用Moonbeam-登录';
?>
<div class="col-sm-5">
    <div class="login_image"><?php echo CHtml::image(Yii::app()->baseUrl . '/img/user.jpg', '欢迎用户登陆'); ?></div>
</div>
<div class="col-sm-7">
    <div class="form_box">
        <div><?php echo CHtml::errorSummary($model, '错误:'); ?></div>
        <?php $form = $this->beginWidget('CActiveForm');?>    
        <h1>登录</h1>
        <div class="row">
            <?php echo $form->textField($model, 'username', array('placeholder' => '用户名称')); ?>
        </div>

        <div class="row">
            <?php echo $form->passwordField($model, 'password', array('placeholder' => '登陆密码')); ?>
        </div>

        <div class="row">
            <span class="cbox">
                <?php echo $form->checkBox($model, 'rememberMe', array('class' => 'checkbox')); ?>
                <label class="lblmt">自动登陆</label></span>
        </div>

        <div class="row">
            <?php echo CHtml::submitButton('登录', array('class' => "btn btn-success big_btn",)); ?>
        </div>

        <div class="pages_msg">
            <?php echo CHtml::link('淘宝用户登录', Yii::app()->createUrl('site/taobaoLogin')); ?>
            <?php echo CHtml::link('用户注册', Yii::app()->createUrl('site/register')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>