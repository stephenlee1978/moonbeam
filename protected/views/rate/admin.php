<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '收费管理-' . Yii::app()->name;
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/common.js', CClientScript::POS_HEAD);
?>
<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'rate_msg',
    'htmlOptions' => array('class' => 'msg'),
));
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
                url: "<?php echo $this->createUrl('rate/command'); ?>",
                dataType: 'json',
                data: data,
                async: true,
                success: function(data) {
                    if (data.success == true) {
                        $.fn.yiiGridView.update('rate-grid');
                        return false;
                    }
                }
            });
        }

    });
</script>

<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'rate_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>
<h1>收费管理</h1>

<div class="well">
<?php echo CHtml::link('新建收费', array('rate/create'), array('class' => 'btn')); ?>
    <?php echo CHtml::button('批量删除', array('class' => 'btn delAll')); ?>
 </div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'rate-grid',
    'dataProvider' => $model->search(),
    'filter'=> $model,
    'summaryText' => '查看{start}-{end} | 共{count}个收费设置',
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
            'name' => 'userId',
            'header' => '用户',
            'value' => array($model, 'getUserName'),
            'type' => 'html',
            'filter' => CHtml::listData(User::model()->findAll(), "id", "username"),
        ),
        array(
            'name' => 'station',
            'filter' => CHtml::listData(Station::model()->findAll(), "station", "addr"),
        ),
        'rate',
        'ulimit',
        'llimit',
        'once',
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'template' => '{update}{delete}',
            'deleteConfirmation' => '您确认删除该收费吗？！',
            'deleteButtonLabel' => '删除收费',
            'updateButtonLabel' => '更新收费',
        ),
    ),
));
?>
