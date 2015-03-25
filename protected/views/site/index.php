<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* fix 2014-12-01  */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tooltip.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-popover.js', CClientScript::POS_BEGIN);
$this->pageTitle = '用户登录-' . Yii::app()->name;
?>
<script>
    jQuery(document).ready(function($) {

        $('a[rel=popover]').mouseover(function() {

            $(this).popover('show');

        })
    });
</script>

<div class="content-container">
    <div class="row-fluid">
        <?php echo CHtml::errorSummary($model, '错误:'); ?>
        <?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('class' => 'form-horizontal'))); ?>
        <legend> 用户登录 </legend>

        <div class="control-group">
            <?php echo $form->labelEx($model, 'username', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'username', array('placeholder' => '用户名称', 'class' => 'input-xlarge')); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->labelEx($model, 'password', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $form->passwordField($model, 'password', array('placeholder' => '登陆密码', 'class' => 'input-xlarge')); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <?php echo $form->checkBox($model, 'rememberMe', array('class' => 'checkbox')); ?>
                <label>自动登陆</label>
            </div>
        </div>


        <div class="form-actions">
            <?php echo CHtml::submitButton('用户登录', array('class' => "btn-large btn btn-danger",)); ?>
            <?php
            echo CHtml::link('淘宝用户登录', Yii::app()->createUrl('site/taobaoLogin'), array('class' => "btn-large btn",
                'data-content' => '通过淘宝授权进行自动登陆，但该用户权限有限，如果想使用全部功能，请进行用户注册。',
                'rel' => 'popover', 'data-original-title' => '淘宝用户'));
            ?>
            <?php echo CHtml::Button('用户注册', array('submit' => array("site/register"), 'class' => "btn-large btn")); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>