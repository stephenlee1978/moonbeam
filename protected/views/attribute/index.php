<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '属性设置-' . Yii::app()->name;
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
                url: "<?php echo $this->createUrl('attribute/command'); ?>",
                dataType: 'json',
                data: data,
                async: true,
                success: function(data) {
                    if (data.success == true) {
                        $.fn.yiiGridView.update('attribute-grid');
                        return false;
                    }
                }
            });
        }

    });
</script>

<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'attribute_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>

<h1>属性设置</h1>

<div class="well">
    <?php echo CHtml::link('新建属性设置', array('attribute/create'), array('class' => 'btn')); ?>
<?php echo CHtml::button('批量删除', array('class' => 'btn delAll')); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'attribute-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'summaryText' => '查看{start}-{end} | 共{count}个属性设置',
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
        'header' => '',
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
            'name' => 'property',
            'type' => 'html',
            'value' => array($model, 'getPropertyName'),
            'filter' => Yii::app()->params['attributes'],
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'pattern',
            'editable' => array(
                'type' => 'textarea',
                'title' => '请填写公式',
                'placement' => 'right',
                'url' => $this->createUrl('attribute/update'),
            )
        ),
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'template' => '{delete}',
            'deleteConfirmation' => '您确认删除该属性吗？！',
            'deleteButtonLabel' => '删除属性',
        ),
    ),
));
?>
