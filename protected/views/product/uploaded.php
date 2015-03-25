<?php
$this->pageTitle = '上传列表-' . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/common.js');
?>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(document).on('click', '#upload-grid a.stock', function() {
        if (!confirm('您确认下架该商品吗？！'))
            return false;
        var th = this,
                afterDelete = function() {
        };
        jQuery('#upload-grid').yiiGridView('update', {
            type: 'POST',
            url: jQuery(this).attr('href'),
            success: function(data) {
                if (data == 'true')
                    jQuery('#upload-grid').yiiGridView('update');
            },
        });
        return false;
    });
    jQuery(function($) {

        //选中商品
        var allCheckbox = function(url) {
            var boxs = getCheckbox();

            if (boxs.length <= 0) {
                alert('请您选择需要操作的商品!');
                return;
            }

            var data = {'selectdel[]': boxs};

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: data,
                async: true,
                success: function(data) {
                    if (data.success == true)
                        $.fn.yiiGridView.update('upload-grid');
                }
            });

        }

        //删除所选响应
        $('.delAllBtn').live('click', function() {
            var url = "<?php echo CHtml::normalizeUrl(array('/taobao/deleteAll')); ?>";
            allCheckbox(url);
        });

        //删除所选响应
        $('.stockAllBtn').live('click', function() {
            var url = "<?php echo CHtml::normalizeUrl(array('/taobao/instockAll')); ?>";
            allCheckbox(url);
        });

        //删除所选响应
        $('.collectBtn').live('click', function() {
            $("#upload_form").attr('action', '<?php echo Yii::app()->createUrl('taobao/collect'); ?>');
            $("#upload_form").submit();
        });
    });




    /*]]>*/
</script>

<h1>上传列表 <small>所有操作关联淘宝</small></h1>

<div class="well">
    <?php echo CHtml::link('删除商品', 'javascript:void(0);', array('class' => 'delAllBtn btn')); ?>
    <?php echo CHtml::link('批量更新', 'javascript:void(0);', array('class' => 'collectBtn btn')); ?>
    <?php echo CHtml::link('批量上架', 'javascript:void(0);', array('class' => 'saleAllBtn btn')); ?>
    <?php echo CHtml::link('批量下架', 'javascript:void(0);', array('class' => 'stockAllBtn btn')); ?>
</div>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'upload_form',
    'method' => 'POST',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'upload-grid',
    'dataProvider' => $model->search(),
    'emptyText' => '对不起，您还没有任何商品.',
    'enableHistory' => true,
    'itemsCssClass' => 'table table-striped',
    'template' => '{summary}{pager}{items}{pager}',
    'summaryText' => '查看{start}-{end} | 共{count}件商品',
    'pagerCssClass' => 'pagination pagination-right',
    'pager' => array(
        'header' => '',
        'htmlOptions' => array('class' => ''),
        'selectedPageCssClass' => 'active',
        'previousPageCssClass' => '',
        'hiddenPageCssClass' => 'disabled',
        'cssFile' => false,
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '前一页',
        'firstPageLabel' => '首页',
        'lastPageLabel' => '最后一页'
    ),
    'columns' => array(
        array(
            'selectableRows' => 2,
            'class' => 'CCheckBoxColumn',
            'headerHtmlOptions' => array('width' => '30px'),
            'checkBoxHtmlOptions' => array('name' => 'selid[]', 'class' => 'checkItem'),
        ),
        array(
            'name' => 'images',
            'type' => 'html',
            'header' => '图片',
            'headerHtmlOptions' => array('width' => '80px'),
            'value' => array($model, 'getProductFristImg'),
        ),
        array(
            'name' => 'station',
            'header' => '站点',
        ),
        array(
            'name' => 'subTitle',
            'header' => '标题',
        ),
        array(
            'name' => 'id',
            'header' => '货号',
        ),
        array(
            'name' => 'approveStatus',
            'type' => 'html',
            'header' => '位置',
            'headerHtmlOptions' => array('width' => '80px'),
            'value' => array($model, 'getStatuImg'),
        ),
        array(
            'name' => 'price',
            'header' => '现价(原始)',
        ),
        array(
            'name' => 'stock',
            'header' => '库存',
        ),
        array(
            'name' => 'city',
            'header' => '城市',
        ),
        array(
            'name' => 'uploadTime',
            'header' => '上传时间',
        ),
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'deleteConfirmation' => '您确认删除该商品吗？！',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl(\'taobao/delete/\', array(\'id\'=>$data["id"]))',
            'template' => '{sale}{stock}{delete}',
            'deleteButtonLabel' => '删除商品',
            'buttons' => array(
                'stock' => array(
                    'label' => '商品下架',
                    'options' => array('class' => 'stock'),
                    'url' => 'Yii::app()->controller->createUrl(\'taobao/instock/\', array(\'id\'=>$data["id"]))',
                    'imageUrl' => Yii::app()->request->baseUrl . '/img/instock.png',
                ),
                'sale' => array(
                    'label' => '更新上架',
                    'url' => 'array("/taobao/collect/","id"=>$data["id"])',
                    'imageUrl' => Yii::app()->request->baseUrl . '/img/onsale.png',
                ),
            ),
        ),
    ),
));

$this->endWidget();
?>

<?php $this->widget('widgets.WGotop.WGotop'); ?>