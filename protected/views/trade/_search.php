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
        echo $form->dropDownList($model, 'userID', CHtml::listData(User::model()->findAll(), "id", "username"), array('empty' => '选择用户')); 
    ?>

    <?php echo $form->label($model, 'beginTime') ?>
    <?php
    $this->widget('ext.EJuiDateTimePicker.EJuiDateTimePicker', array(
        'model' => $model,
        'attribute' => 'beginTime',
        'language' => 'zh_cn',
        'options' => array(
            'dateFormat' => 'yy-mm-dd',
            'timeFormat' => 'hh:mm:ss',
        ),
       )
    );
    ?> 

    <?php echo $form->label($model, 'endTime') ?>
    <?php
    $this->widget('ext.EJuiDateTimePicker.EJuiDateTimePicker', array(
        'model' => $model,
        'attribute' => 'endTime',
        'language' => 'zh_cn',
        'options' => array(
            'dateFormat' => 'yy-mm-dd',
            'timeFormat' => 'hh:mm:ss',
        ),
            )
    );
    ?> 
</div>

<div class="form-actions">
    <?php echo CHtml::submitButton('搜索交易', array('class' => 'btn',)); ?>
    <?php echo CHtml::ajaxSubmitButton('搜索淘宝交易', array('taobao/trade'), array(), array('class' => 'btn')) ?>
    <?php echo CHtml::button('导出上传历史', array('id' => 'export_upload_btn', 'class' => 'btn btn-danger')); ?>
    <?php echo CHtml::button('导出交易订单', array('id' => 'export_oreder_btn', 'class' => 'btn btn-danger')); ?>
</div>
<?php $this->endWidget(); ?>