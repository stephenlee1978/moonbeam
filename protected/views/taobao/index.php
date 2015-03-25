<?php
$this->pageTitle = '商品采集-' . Yii::app()->name;
?>

<script type="text/javascript">
    $(function() {
        $("select[name=station]").change(function() {
            var url = $(this).val();
            $("#url_link").attr("href", url);
        });

    });

</script>

<div class="fb_mtm">
    <div class="mtm_info">
        <div class="mtm_title">
            <span><?php echo CHtml::image(Yii::app()->baseUrl . '/img/scrap.png', ''); ?> 商品采集</span>
        </div>
    </div>
    <div class="mtm_content">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'collect_form',
            'action' => Yii::app()->createUrl('taobao/index'),
            'enableAjaxValidation' => true,
            'method' => 'POST',
            'htmlOptions' => array('target' => 'iframe', 'class' => 'well form-search', 'novalidate' => 'novalidate'),
        ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <?php echo $form->dropDownList($model, 'city', CHtml::listData(City::model()->findAll(), "id", "city"), array('empty' => '选择城市网点')); ?>
            <?php echo $form->dropDownList($model, 'list', $model->getListData()); ?>
            <?php echo CHtml::dropDownList('station', '', CHtml::listData(Station::model()->findAll(), "addr", "station"), array('empty' => '支持站点')); ?>
            <?php echo CHtml::link('逛官网', 'javascript:void(0)', array('id' => 'url_link', 'target' => '_blank', 'class' => 'qf_btn')); ?>
        </div>

        <div class="row">
            <?php echo $form->textField($model, 'url', array('placeholder' => '输入需要采集的URL地址', 'class' => 'input-block-level search-query')); ?>
            <?php
            echo CHtml::submitButton('商品采集', array('class' => 'qf_btn'));
            ?>
        </div>
        <?php $this->endWidget(); ?>

        <p><p/>

        <iframe width="100%" height="500" name="iframe" class="form-search" style="padding: 0px;"></iframe>

    </div>
</div>

