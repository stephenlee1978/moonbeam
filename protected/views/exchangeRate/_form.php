<?php
/* @var $this RateController */
/* @var $model Rate */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'exchangerate-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    )
        ));
?>

<?php echo $form->errorSummary($model); ?>


<div class="control-group">
    <?php echo $form->labelEx($model, 'userID'); ?>
    <div class="controls">
        <?php 
        if(!Yii::app()->user->isAdministrator()){
            echo CHtml::textField('userID', User::getUserName());
            echo $form->hiddenField($model, 'userID');
        }else{
            echo $form->dropDownList($model, 'userID', CHtml::listData(User::Model()->findAll(), 'id', 'username'), array('empty' => '--选择所有用户--')); 
        }
        ?>
    </div>
    <p class="help-block">选择所有用户均被设置同样汇率公式</p>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'station'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'station', CHtml::listData(Station::Model()->findAll(), 'station', 'station'), 
            array('empty'=>'--选择所有站点--')); ?>
        <p class="help-block">选择所有站点均被设置同样汇率公式</p>
    </div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'unitID'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'unitID', CHtml::listData(Unit::Model()->findAll(), 'id', 'remark'), 
            array('empty'=>'--选择所有货币--')); ?>
        <p class="help-block">选择所有货币均被设置同样汇率公式</p>
    </div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'isoff'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'isoff', array(1=>'特价', 0=>'非特价'), 
            array('empty'=>'--特价类型--')); ?>
        <p class="help-block">选择特价, 汇率公式在特价时使用。</p>
    </div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'pattern'); ?>
    <div class="controls"><?php echo $form->textField($model, 'pattern'); ?>
        <p class="help-block">如港币公式为:{value}*0.85</p>
    </div>
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton($model->isNewRecord ? '新建汇率' : '保存汇率', array('class' => 'btn btn-success',)); ?>
</div>

<?php $this->endWidget(); ?>
