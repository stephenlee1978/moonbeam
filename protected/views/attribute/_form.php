<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl .'/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl .'/js/bootstrap-popover.js', CClientScript::POS_HEAD);
?>
<script>
    jQuery(document).ready(function($) {

        $('.icon-question-sign').mouseover(function() {

            $(this).popover('show');

        })
    });
</script>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'attribute-form',
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
        echo $form->dropDownList($model, 'userID', CHtml::listData(User::Model()->findAll(), 'id', 'username'), array('empty' => '--选择所有用户--'));
    }
    ?></div>
    <p class="help-block">选择所有用户均被设置同样设置</p>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'property'); ?>
    <div class="controls"><?php echo $form->dropDownList($model, 'property', Yii::app()->params['attributes'], 
            array('empty'=>'选择属性')); ?>
    </div>
</div>

<div class="control-group">
    <?php echo $form->labelEx($model, 'pattern'); ?>
    <div class="controls"><?php echo $form->textField($model, 'pattern'); ?></div>
    <p class="help-block"><i class="icon-question-sign" rel = 'popover', data-content='公式:如重量:65*{value} 标题:新品{value}十天到货', data-original-title= '公式提示'></i></p>
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton($model->isNewRecord ? '新建属性设置' : '保存属性设置', array('class' => 'btn btn-success',)); ?>
</div>

<?php $this->endWidget(); ?>
