<?php
/* @var $this ProductController */
/* @var $model Product */
$this->pageTitle = $model->productTitle . Yii::app()->name;
;
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-popover.js', CClientScript::POS_HEAD);
?>

<script type="text/javascript">
    /*<![CDATA[*/

    /*]]>*/
</script>

<?php if ($this->beginCache('pv' . $model->id . strtotime($model->updateTime), array('duration' => 3600))) { ?>
<div class="fb_form">
    <div class="pagelet">
        <div class="fb_declare">
            <div class="clearfix">
                <h3 class="fb_title">
                    <?php echo CHtml::image(Yii::app()->baseUrl . '/img/view.png', ''); ?>
<?php echo $model->brandName . ' ' . $model->productTitle; ?></h3>
            </div>
            <?php echo $this->renderPartial('_panel', array('model' => $model)); ?>
        </div>
        <div class="fb_info">
            <div class="span4">
                <?php
                $this->widget('application.widgets.WThumbs.WThumbs', array('model' => $model));
                ?>
            </div>

                <div class="span8">
                    <?php
                    $this->widget('zii.widgets.CDetailView', array(
                        'htmlOptions' => array('class' => 'table','style'=>'width: 620px;',),
                        'nullDisplay' => '未设置',
                        'data' => $model,
                        'attributes' => array(
                            'id',
                            'updateTime',
                            array(
                                'label' => '来源',
                                'type' => 'raw',
                                'value' => $model->getUrlLink(),
                            ),
                            array(
                                'label' => '货存',
                                'name' => 'stock',
                            ),
                            array(
                                'label' => '重量',
                                'type' => 'raw',
                                'value' => $model->weight . ' 重量运费=￥' . Attribute::countWeightCost($model->weight),
                            ),
                            'upload',
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
                                'label' => '成本',
                                'type' => 'raw',
                                'value' => $model->showRealPriceDes(),
                            ),
                            array(
                                'label' => '现价',
                                'type' => 'raw',
                                'value' => $model->showPriceDes(),
                            ),
                            array(
                                'label' => '描述',
                                'name' => 'desc',
                                'type' => 'html',
                            ),
                            array(
                                'label' => '细节',
                                'name' => 'details',
                                'type' => 'html',
                            ),
                            array(
                                'label' => '设计师',
                                'name' => 'designer',
                                'type' => 'html',
                            ),
                            array(
                                'label' => '尺寸介绍',
                                'name' => 'sizeFitContainer',
                                'type' => 'html',
                            ),
                        ),
                    ));
                    ?>               
                </div>
            <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <?php $this->endCache();
} ?>

<?php $this->widget('widgets.WGotop.WGotop'); ?>