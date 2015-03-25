<?php
/* @var $this CityController */
$this->pageTitle = '城市管理-' . Yii::app()->name;
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl .'/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl .'/js/bootstrap-popover.js', CClientScript::POS_HEAD);
?>

<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'city_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>

<h1>城市管理</h1>

<div class="well">
    <?php echo CHtml::link('新建城市', array('city/create'), array('class' => 'btn')); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'city-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'summaryText' => '查看{start}-{end} | 共{count}个城市设置',
    'template' => '{summary}{pager}{items}{pager}',
    'enableHistory' => true,
    'itemsCssClass' => 'table table-striped',
    'pagerCssClass'=>'pagination pagination-right',
    'pager' => array(
        'header' => '',
        'htmlOptions' => array('class' => ''),
        'selectedPageCssClass' => 'active',
        'previousPageCssClass' => '',
        'hiddenPageCssClass' => 'disabled',  
        'cssFile' => false,  
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '前一页',
        'firstPageLabel'=>'首页',
        'lastPageLabel'=>'最后一页'
    ),
    'columns' => array(
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'state',
            'editable' => array(
                'type' => 'text',
                'title'=>'请填写国家',
                'placement' => 'right',
                'url' => $this->createUrl('city/update'),
            )
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'city',
            'editable' => array(
                'type' => 'text',
                'title'=>'请填写城市',
                'placement' => 'right',
                'url' => $this->createUrl('city/update'),
            )
        ),
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'template' => '{delete}',
            'deleteConfirmation' => '您确认删除该城市吗？！',
            'deleteButtonLabel' => '删除城市',
        ),
    ),
));
?>