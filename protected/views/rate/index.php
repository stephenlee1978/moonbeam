<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '收费管理-' . Yii::app()->name;

?>

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
            'name' => 'station',
            'filter' => CHtml::listData(Station::model()->findAll(), "station", "addr"),
        ),
        'rate',
        'ulimit',
        'llimit',
        'once',
    ),
));
?>
