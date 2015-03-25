<?php
/* @var $this SiteController */
/* @var $model RegisterForm */
/* stephen fix 2015-1-5  */
$this->pageTitle = '欢迎使用Moonbeam-注册' . Yii::app()->name;
?>
<div class="col-sm-5">
    <div class="login_image"><?php echo CHtml::image(Yii::app()->baseUrl . '/img/taobao.png', '欢迎用户注册'); ?></div>
</div>

<div class="col-sm-7">
    <div class="form_box">
        <div><?php echo CHtml::errorSummary($model, '错误:'); ?></div>

        <?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('class' => 'form-horizontal'))); ?>

        <h1>注册</h1>

        <div class="row">
            <?php echo $form->textField($model, 'username', array('placeholder' => '用户名称')); ?>
        </div>

        <div class="row">
            <?php echo $form->passwordField($model, 'password', array('placeholder' => '用户密码')); ?>
        </div>

        <div class="row">
            <?php echo $form->passwordField($model, 'repassword', array('placeholder' => '重复密码')); ?>
        </div>

        <div class="row">
            <?php echo $form->textField($model, 'email', array('placeholder' => '用户邮件地址')); ?>
        </div>

        <div class="row">
            <span class="cbox">
                <label>验证码</label>
                <?php
                $this->widget('CCaptcha', array('showRefreshButton' => false, 'clickableImage' => true,
                    'imageOptions' => array('alt' => '点击换图', 'title' => '点击换图',)));
                ?>
            </span>
        </div>

        <?php if (CCaptcha::checkRequirements()): ?>
            <div class="row">
                    <?php echo $form->textField($model, 'verifyCode', array('placeholder' => '填写验证码')); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php echo CHtml::submitButton('注册', array('class' => 'big_btn btn btn-success')); ?>
        </div>

        <div class="pages_msg">
            <?php echo CHtml::link('淘宝用户登录', Yii::app()->createUrl('site/taobaoLogin')); ?>
            <?php echo CHtml::link('使用已有用户登录', Yii::app()->createUrl('site/login')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>