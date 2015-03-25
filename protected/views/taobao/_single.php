<div class="span4" style="overflow: hidden;text-overflow: ellipsis;white-space:nowrap;">
<?php echo $model->getProductBigImg(); ?>
    <?php
    $this->widget('zii.widgets.CDetailView', array(
        'htmlOptions' => array('class' => 'table table-striped'),
        'nullDisplay' => '未设置',
        'data' => $model,
        'attributes' => array(
            'id',
            array(
                'label' => '来源',
                'type' => 'raw',
                'value' => $model->getUrlLink(),
            ),
            array(
                'label' => '货存',
                'type' => 'raw',
                'value' => $model->getStock(),
            ),
            array(
                'label' => '颜色',
                'type' => 'raw',
                'value' => $model->showSwatche(),
            ),
            array(
                'label' => '尺寸',
                'type' => 'raw',
                'value' => $model->showSize(),
            ),
            array(
                'label' => '成本价',
                'type' => 'raw',
                'value' => "<span class='label badge-success'>{$model->unit}{$model->price}</span>",
            ),
            array(
                'label' => '淘宝价',
                'type' => 'raw',
                'value' => '<div class=taobaototal>' . $model->showTaobaoPriceDes() . '</div>',
            ),
        ),
    ));
    ?>
</div>