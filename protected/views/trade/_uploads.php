<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'uploads-grid',
    'dataProvider' => $model->searchUploads(),
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
        'productId',
        'num_iid',
        'station',
        array(
            'name' => 'url',
            'value' => array($model, 'getProductUrl'),
            'type' => 'html',
        ),
         'cost',
    ),
));
?>
