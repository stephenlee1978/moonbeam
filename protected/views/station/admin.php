<?php
$this->pageTitle = '站点管理-' . Yii::app()->name;

?>
<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'station_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>

<h1>站点管理</h1>

<div class="well">
<?php echo CHtml::link('新建站点', array('station/create'), array('class' => 'btn')); ?>
 </div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'station-grid',
    'dataProvider' => $model->search(),
    'filter'=> $model,
    'summaryText' => '查看{start}-{end} | 共{count}个站点',
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
        'station',
        'addr',
        'captureClass',
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'template' => '{update}{delete}',
            'deleteConfirmation' => '您确认删除该站点吗？！',
            'deleteButtonLabel' => '删除站点',
            'updateButtonLabel' => '更新站点',
        ),
    ),
));
?>

<div class="cleaner"></div>
</div> <!-- END of mainform -->
