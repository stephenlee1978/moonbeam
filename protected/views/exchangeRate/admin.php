<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '汇率管理-' . Yii::app()->name;
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
                url: "<?php echo $this->createUrl('exchangeRate/command'); ?>",
                dataType: 'json',
                data: data,
                async: true,
                success: function(data) {
                    if (data.success == true) {
                        $.fn.yiiGridView.update('exchangerate-grid');
                        return false;
                    }
                }
            });
        }

    });
</script>

<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'erate_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>

<h1>汇率管理</h1>

<div class="well">
<?php echo CHtml::link('新建汇率公式', array('exchangeRate/create'), array('class' => 'btn')); ?>
<?php echo CHtml::button('批量删除', array('class' => 'btn delAll')); ?>
  </div>

    <?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'exchangerate-copyform',
    'action'=>  CHtml::normalizeUrl(array('exchangeRate/copyconfig')),
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    )
        ));

    echo CHtml::dropDownList('fuserID', '', CHtml::listData(User::Model()->findAll(), 'id', 'username'), array('empty' => '--选择复制用户--')); 
    
    echo CHtml::dropDownList('tuserID', '', CHtml::listData(User::Model()->findAll(), 'id', 'username'), array('empty' => '--选择被复制用户--')); 
    
    echo CHtml::submitButton('复制配置', array('class'=>'btn'));

$this->endWidget(); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'exchangerate-grid',
    'dataProvider' => $model->search(),
    'filter'=> $model,
    'summaryText' => '共{count}个汇率设置',
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
            'name' => 'userID',
            'value' => array($model, 'getUserName'),
            'type' => 'html',
            'filter' => CHtml::listData(User::model()->findAll(), "id", "username"),
        ),
        array(
            'name' => 'station',
            'filter' => CHtml::listData(Station::model()->findAll(), "station", "station"),
        ),
        array(
            'name' => 'unitID',
            'value' => array($model, 'getUnitName'),
            'filter' => CHtml::listData(Unit::model()->findAll(), "id", "remark"),
        ),
        array(
            'name' => 'isoff',
            'value' => array($model, 'getIsOff'),
            'filter' => array(1=>'特价', 0=>'非特价'),
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'pattern',
            'editable' => array(
                'type' => 'textarea',
                'title' => '请填写公式',
                'placement' => 'right',
                'url' => $this->createUrl('exchangeRate/ajaxUpdate'),
            )
        ),
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'template' => '{update}{delete}',
            'deleteConfirmation' => '您确认删除该汇率吗？！',
            'deleteButtonLabel' => '删除汇率',
            'updateButtonLabel' => '更新汇率',
        ),
    ),
));
?>