<?php
$this->pageTitle = '商品管理-' . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/common.js');
?>

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {

        //删除选中商品
        var delAllCheckbox = function() {
            var boxs = getCheckbox();

            if (boxs.length <= 0) {
                alert('请您选择需要删除的商品!');
                return;
            }

            var data = {'selectdel[]': boxs};

            $.ajax({
                type: 'POST',
                url: "<?php echo CHtml::normalizeUrl(array('/product/deleteAll')); ?>",
                dataType: 'json',
                data: data,
                async: true,
                success: function(data) {
                    if (data.success == true){
                        $.fn.yiiGridView.update('product-grid',
                            {
                                type: 'GET',
                                url: "<?php echo CHtml::normalizeUrl(array('/product/admin')); ?>",
                                data :$('#psearch_form').serialize()
                            });
                    }
                }
            });

        }

        //删除所选响应
        $('.delAllBtn').live('click', function() {
            delAllCheckbox();
        });
    });




    /*]]>*/
</script>

<h1>商品管理</h1>

<?php echo $this->renderPartial('_search', array('model' => $model)); ?>

<div class="well">
    <?php echo CHtml::link('删除所选', 'javascript:void(0);',array('class'=>'delAllBtn btn')); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'product-grid',
    'dataProvider' => $model->search(),
    'emptyText' => '对不起，您还没有任何商品.',
    'enableHistory' => true,
    'itemsCssClass' => 'table table-striped',
    'template' => '{summary}{pager}{items}{pager}',
    'summaryText' => '查看{start}-{end} | 共{count}件商品',
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
            'selectableRows' => 2,
            'class' => 'CCheckBoxColumn',
            'headerHtmlOptions' => array('width' => '30px'),
            'checkBoxHtmlOptions' => array('name' => 'selid[]', 'class'=>'checkItem'),
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
            'name' => 'brandName',
            'header' => '品牌',
        ),
        array(
            'name' => 'productTitle',
            'header' => '标题',
        ),
        array(
            'name' => 'pid',
            'header' => '货号',
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
            'name' => 'updateTime',
            'header' => '更新时间',
        ),
        array(
            'name' => 'uploadTime',
            'header' => '是否上传',
            'value' => array($model, 'isUpload'),
            'type' => 'html',
            'htmlOptions' => array('width' => '50'),
        ),
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'afterDelete' => 'function(link,success,data){ if(success) alert("删除成功"); }',
            'deleteConfirmation' => '您确认删除该商品吗？！',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl(\'product/delete/\', array(\'id\'=>$data["id"]))',
            'template' => '{delete}',
            'deleteButtonLabel' => '删除商品',
        ),
    ),
));
?>

<?php $this->widget('widgets.WGotop.WGotop'); ?>