<?php
$this->pageTitle = Yii::app()->name . '-商品列表';
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/common.js", CClientScript::POS_BEGIN);
?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.itemimg').live('mouseover', function() {
            $(this).find('.btn_tool').show();
            $(this).parent('.thumbitem').css({"padding-top": "8px"});
        })
        $('.itemimg').live('mouseout', function() {
            $(this).find('.btn_tool').hide();
            $(this).parent('.thumbitem').css({"padding-top": "4px"});
        })
        $('.titleTooltip').live('mouseover', function() {
            $(this).tooltip('show');
        })
    });
</script> 

<?php echo $this->renderPartial('_search', array('model' => $model)); ?>

<div class="row-fluid">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'plist_form',
        'method' => 'POST',
        'htmlOptions'=>array('style'=>'background-color:transparent;border:none;box-shadow:none;'),
    ));

    $this->widget('widgets.EColumnListView', array(
        'id' => 'product_list',
        'dataProvider' => $dataProvider,
        'template' => '{items}{pager}',
        'itemView' => '_view',
        'ajaxUpdate' => true,
        'itemsTagName' => 'ul',
        'itemsCssClass' => 'thumbnails',
        'itemsCssId'=>'thumbnailsid',
        'emptyText' => '对不起，没有搜索到商品!',
    ));
    
    $this->endWidget();
    ?>
</div>
<?php
$this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
    'contentSelector' => '#thumbnailsid',
    'itemSelector' => 'li.customspan',
    'loadingText' => '正在加载商品...',
    'donetext' => '没有查询到商品!',
    'loadingImg'=>Yii::app()->baseUrl . '/img/loading.gif',
    'pages' => $dataProvider->getPagination(),
));
?>
<?php $this->widget('widgets.WGotop.WGotop'); ?>


