<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => false,
    'htmlOptions'=>array(
        'class'=>'form-horizontal'
    )
        ));
?>

<?php echo $form->errorSummary($model, "发生错误:"); ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'username'); ?>
    <div class="controls"><?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 128)); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'password'); ?>
    <div class="controls"><?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 128)); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'sex'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'sex', array(0 => '女', 1 => '男')); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'email'); ?>
    <div class="controls"><?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128)); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'level'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'level', array(2 => '系统管理员', 1 => '超级用户')); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'active'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'active', array(1 => '激活', 0 => '未激活')); ?></div>
</div>


<div class="form-actions">
<?php echo CHtml::submitButton($model->isNewRecord ? '新建用户' : '保存用户', array('class' => 'btn btn-success',)); ?>
<?php echo CHtml::htmlButton('返回', array('class' => 'btn', 'onclick' => 'window.history.back()')); ?></div>
</div>

<?php $this->endWidget(); ?>