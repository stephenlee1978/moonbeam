<?php
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'station-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    )
        ));
?>

<?php echo $form->errorSummary($model); ?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'station'); ?>
    <div class="controls"><?php echo $form->textField($model, 'station'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'addr'); ?>
    <div class="controls"><?php echo $form->textField($model, 'addr'); ?></div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'captureClass'); ?>
    <div class="controls"><?php echo $form->textField($model, 'captureClass'); ?></div>
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton($model->isNewRecord ? '新建站点' : '保存站点', array('class' => 'btn btn-success',)); ?>
</div>

<?php $this->endWidget(); ?>
