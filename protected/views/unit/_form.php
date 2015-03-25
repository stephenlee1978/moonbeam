<?php
/* @var $this RateController */
/* @var $model Rate */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'unit-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    )
        ));
?>

<?php echo $form->errorSummary($model); ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'id'); ?>
    <div class="controls"><?php echo $form->textField($model, 'id'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'sign'); ?>
    <div class="controls"><?php echo $form->textField($model, 'sign'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'remark'); ?>
    <div class="controls"><?php echo $form->textField($model, 'remark'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'rate'); ?>
    <div class="controls"><?php echo $form->textField($model, 'rate'); ?>
    <p class="help-block">汇率值:当前货币=人民币</p></div>
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton($model->isNewRecord ? '新建货币' : '保存货币', array('class' => 'btn btn-success',)); ?>
</div>

<?php $this->endWidget(); ?>
