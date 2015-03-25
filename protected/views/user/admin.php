<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = '用户管理-' . Yii::app()->name;
?>

<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'user_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>

<h1>用户管理</h1>

<div class="well">
    <?php echo CHtml::link('新建用户', Yii::app()->createUrl('user/create'), array('class'=>'btn'))?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'summaryText' => '查看{start}-{end} | 共{count}个用户',
    'filter' => $model,
    'enableHistory' => true,
    'itemsCssClass' => 'table table-striped',
    'template' => '{summary}{pager}{items}{pager}',
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
        'userID',
        'username',
        'nickname',
        'email',
        'logintime',
        array(
                    'name' => 'level',
                    'value' => array($model, 'getLevelState'),
                    'headerHtmlOptions' => array('style' => 'width: 50px'),
                    'type' => 'html',
                ),
        array(
                    'name' => 'active',
                    'value' => array($model, 'getActiveState'),
                    'headerHtmlOptions' => array('style' => 'width: 50px'),
                    'type' => 'html',
                ),
        'outtime',
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'template' => '{update}{delete}',
            'deleteConfirmation' => '您确认删除该用户吗？！',
            'deleteButtonLabel' => '删除用户',
            'updateButtonLabel' => '更新用户',
        ),
    ),
));
?>
