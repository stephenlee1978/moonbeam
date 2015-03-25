<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '运费管理-' . Yii::app()->name;
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-popover.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/common.js', CClientScript::POS_HEAD);
?>
<script>
    jQuery(document).ready(function($) {
        //批量删除
        $('.delAll').live('click', function() {
            var sels = getCheckbox();
            if (sels.length <= 0)
                return;

            ajaxRequest(sels, 'delAll');

        });

        function ajaxRequest(sels, cmd) {

            var data = {'selid[]': sels, 'cmd': cmd};

            $.ajax({
                type: 'POST',
                url: "<?php echo $this->createUrl('express/command'); ?>",
                dataType: 'json',
                data: data,
                async: true,
                success: function(data) {
                    if (data.success == true) {
                        $.fn.yiiGridView.update('express-grid');
                        return false;
                    }
                }
            });
        }

    });
</script>

<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'express_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>

<h1>运费管理</h1>

<div class="well">
<?php echo CHtml::link('新建运费', array('express/create'), array('class' => 'btn')); ?>
    <?php echo CHtml::button('批量删除', array('class' => 'btn delAll')); ?>
 </div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'express-grid',
    'dataProvider' => $model->search(),
    'filter'=> $model,
    'summaryText' => '共{count}个运费设置',
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
            'class' => 'CCheckBoxColumn',
            'selectableRows' => 2,
            'headerHtmlOptions' => array('width' => '30px'),
            'checkBoxHtmlOptions' => array('name' => 'selid[]'),
        ),
        array(
            'name' => 'station',
            'filter' => CHtml::listData(Station::model()->findAll(), "station", "station"),
        ),
        array(
            'name' => 'unitID',
            'value'=>  'Unit::getRemarkByPk($data->unitID)',
            'filter' => CHtml::listData(Unit::model()->findAll(), "id", "remark"),
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'cost',
            'editable' => array(
                'type' => 'text',
                'title' => '请填写运费费用',
                'placement' => 'right',
                'url' => $this->createUrl('express/ajaxUpdate'),
            )
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'maxFree',
            'editable' => array(
                'type' => 'text',
                'title' => '请填写免运费价格',
                'placement' => 'right',
                'url' => $this->createUrl('express/ajaxUpdate'),
            )
        ),
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'template' => '{update}{delete}',
            'deleteConfirmation' => '您确认删除该运费设置吗？！',
            'deleteButtonLabel' => '删除运费',
            'updateButtonLabel' => '更新运费',
        ),
    ),
));
?>