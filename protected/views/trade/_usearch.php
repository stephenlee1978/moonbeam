<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'trade_form',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'POST',
    'htmlOptions' => array('class' => 'well form-search')
        ));
?>
<div class="row">
    <?php echo $form->errorSummary($model); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'userID'); ?>
    <?php
            echo CHtml::textField('userID', User::getUserName());
            echo $form->hiddenField($model, 'userID');
    ?>

    <?php echo $form->label($model, 'month') ?>
    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'model' => $model,
        'attribute' => 'month',
        'language' => 'zh_cn',
        'options' => array(
            'dateFormat' => 'yy-mm',
        ),
       )
    );
    ?> 
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton('搜索交易', array('class' => 'btn',)); ?>
    <?php echo CHtml::button('导出上传历史', array('id' => 'export_upload_btn', 'class' => 'btn btn-danger')); ?>
    <?php echo CHtml::button('导出交易订单', array('id' => 'export_oreder_btn', 'class' => 'btn btn-danger')); ?>
</div>
<?php $this->endWidget(); ?>