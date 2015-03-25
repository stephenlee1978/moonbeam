<?php
/* @var $this RateController */
/* @var $model Rate */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'express-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    )
        ));
?>

<?php echo $form->errorSummary($model); ?>


<div class="control-group">
    <?php echo $form->labelEx($model, 'userID'); ?>
    <div class="controls"><?php
    if(!Yii::app()->user->isAdministrator()){
            echo CHtml::textField('userID', User::getUserName());
            echo $form->hiddenField($model, 'userID');
    }else{
        echo $form->dropDownList($model, 'userID', CHtml::listData(User::Model()->findAll(), 'id', 'username'), array('empty' => '--选择所有用户--')); }
    ?></div>
    <p class="help-block"></p>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'station'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'station', CHtml::listData(Station::Model()->findAll(), 'station', 'station'), 
            array('empty'=>'选择站点')); ?>
        <p class="help-block"></p>
    </div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'unitID'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'unitID', CHtml::listData(Unit::Model()->findAll(), 'id', 'remark'), 
            array('empty'=>'选择货币')); ?>
        <p class="help-block"></p>
    </div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'cost'); ?>
    <div class="controls"><?php echo $form->textField($model, 'cost'); ?>
        <p class="help-block">（人民币）</p>
    </div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'maxFree'); ?>
    <div class="controls"><?php echo $form->textField($model, 'maxFree'); ?>
        <p class="help-block">（按上面所选货币）</p>
    </div>
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton($model->isNewRecord ? '新建运费' : '保存运费', array('class' => 'btn btn-success',)); ?>
</div>

<?php $this->endWidget(); ?>
