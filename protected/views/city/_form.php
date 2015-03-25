<?php
/* @var $this RateController */
/* @var $model Rate */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'city-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    )
        ));
?>

<?php echo $form->errorSummary($model); ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'state'); ?>
    <div class="controls"><?php echo $form->textField($model, 'state'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'city'); ?>
    <div class="controls"><?php echo $form->textField($model, 'city'); ?></div>
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton($model->isNewRecord ? '新建城市' : '保存城市', array('class' => 'btn btn-success',)); ?>
</div>

<?php $this->endWidget(); ?>
