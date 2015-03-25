<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-popover.js', CClientScript::POS_HEAD);
?>

<div class="fb_ca clearfix col-rt">
    <?php
        echo '切换货源 ';
        $this->widget('ext.editable.EditableField', array(
            'type' => 'select',
            'model' => $model,
            'attribute' => 'city',
            'url' => $this->createUrl('product/update'),
            'source' => CHtml::listData(City::model()->findAll(), 'city', 'city'),
            'placement' => 'bottom',
            'title'=>'选择货源国'
        ));
        echo ' ';
        echo CHtml::link('更新商品', array('/taobao/collect', 'id' => $model->id), array('id' => "upload", 'class' => 'qf_btn',));
        echo CHtml::link('上传淘宝', array('/taobao/edit', 'id' => $model->id), array('id' => "upload", 'class' => 'qf_btn',));
    ?>
</div>