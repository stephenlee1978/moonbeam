<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'statistics-grid',
    'dataProvider' => $model->searchStatistics(),
    'template' => '{pager}{items}{pager}',
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
            'name' => 'userId',
            'header' => '用户',
            'value' => array($model, 'getUserName'),
            'type' => 'html',
        ),
        array(
            'name' => 'pic_path',
            'header' => '图片',
            'value' => array($model, 'getPiture'),
            'type' => 'html',
        ),
        'tid',
        'tradetime',
        'paytime',
        'title',
        'payment',
        'buyer_nick',
        'receiver',
        'cost',
    ),
));
?>