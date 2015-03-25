<?php
/* @var $this CityController */
$this->pageTitle = '货币管理-' . Yii::app()->name;
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-popover.js', CClientScript::POS_HEAD);
?>
<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'unit_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>

<h1>货币管理<small> 请谨慎进行货币设置，它将关联到用户的计算公式</small></h1>

<div class="well">
    <?php echo CHtml::link('新建货币', array('unit/create'), array('class' => 'btn')); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'unit-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'summaryText' => '查看{start}-{end} | 共{count}个货币设置',
    'template' => '{summary}{pager}{items}{pager}',
    'enableHistory' => true,
    'itemsCssClass' => 'table table-striped',
    'pagerCssClass' => 'pagination pagination-right',
    'pager' => array(
        'header' => '',
        'htmlOptions' => array('class' => ''),
        'selectedPageCssClass' => 'active',
        'previousPageCssClass' => '',
        'hiddenPageCssClass' => 'disabled',
        'cssFile' => false,
        'header' => '',
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '前一页',
        'firstPageLabel' => '首页',
        'lastPageLabel' => '最后一页'
    ),
    'columns' => array(
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'id',
            'editable' => array(
                'type' => 'text',
                'title' => '请填写货币标示',
                'placement' => 'right',
                'url' => $this->createUrl('unit/ajaxUpdate'),
            )
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'sign',
            'editable' => array(
                'type' => 'text',
                'title' => '货币符号',
                'placement' => 'right',
                'url' => $this->createUrl('unit/ajaxUpdate'),
            )
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'remark',
            'editable' => array(
                'type' => 'text',
                'title' => '请填写货币描述',
                'placement' => 'right',
                'url' => $this->createUrl('unit/ajaxUpdate'),
            )
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'rate',
            'editable' => array(
                'type' => 'text',
                'title' => '汇率值',
                'placement' => 'right',
                'url' => $this->createUrl('unit/ajaxUpdate'),
            )
        ),
        'rateTime',
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'template' => '{upaterate}{delete}',
            'deleteConfirmation' => '您确认删除该货币吗？！',
            'deleteButtonLabel' => '删除货币',
            'buttons' => array(
                'upaterate' => array(
                    'label' => '实时更新该货币汇率值',
                    'imageUrl' => Yii::app()->request->baseUrl . '/img/rate.png',
                    'url' => 'Yii::app()->createUrl("unit/update", array("id"=>$data->id))',
                    'options' => array(
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => 'js:$(this).attr("href")',
                            'success' => 'function(ret){
                                    jQuery("#unit-grid").yiiGridView("update");
                                    return false;
                                
                             }',
                        ),
                    ),
                ),
            ),
        ),
    ),
));
?>