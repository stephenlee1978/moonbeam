<?php
/* @var $this RateController */
/* @var $model Rate */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'rate-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    )
        ));
?>

<?php echo $form->errorSummary($model); ?>


<div class="control-group">
    <?php echo $form->labelEx($model, 'userId'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'userId', CHtml::listData(User::Model()->findAll(), 'id', 'username'), array('empty' => '--选择所有用户--')); ?></div>
    <p class="help-block">选择所有用户均被设置同样收费</p>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'station'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'station', CHtml::listData(Station::Model()->findAll(), 'station', 'addr'), 
            array('empty'=>'选择所有站点')); ?>
        <p class="help-block">选择所有站点均被设置同样收费</p>
    </div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'rate'); ?>
    <div class="controls"><?php echo $form->textField($model, 'rate'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'ulimit'); ?>
    <div class="controls"><?php echo $form->textField($model, 'ulimit'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'llimit'); ?>
    <div class="controls"><?php echo $form->textField($model, 'llimit'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'once'); ?>
    <div class="controls"><?php echo $form->textField($model, 'once'); ?></div>
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton($model->isNewRecord ? '新建收费' : '保存收费', array('class' => 'btn btn-success',)); ?>
</div>

<?php $this->endWidget(); ?>
