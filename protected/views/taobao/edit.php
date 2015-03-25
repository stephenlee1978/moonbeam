<?php
$this->pageTitle = '淘宝属性编辑-' . Yii::app()->name;
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/common.js", CClientScript::POS_HEAD);
Yii::app()->clientscript->registerScriptFile(Yii::app()->baseUrl . '/js/arttext.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-popover.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tab.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.validate.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/taobao.js", CClientScript::POS_BEGIN);
?>

<div class="fb_form">
    <div class="pagelet">
        <div class="fb_declare">
            <div class="clearfix">
                <h3 class="fb_title">
                    <?php echo CHtml::image(Yii::app()->baseUrl .'/img/edit.png',''); ?>
                    <?php echo $edit->getDeclare(); ?></h3>
                </div>
        </div>
        <div class="fb_info">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'propForm',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'action' => Yii::app()->createUrl($this->route, array('id' => $id)),
        'method' => 'POST',
        'htmlOptions' => array(
            'style' => 'padding:0px;border:none;'
        )
    ));
    ?>

    <?php
    if ($edit->auto) {
        $this->renderPartial('_auto', array('models' => $models));
    } else {
        $this->renderPartial('_single', array('model' => $models[0]));
    }
    ?>
    <div class="span8">
        <div class="form-actions">
            <?php echo CHtml::submitButton('确认上传', array('class' => 'btn btn-large btn-danger upload_btn')); ?>
        </div>
        <?php echo CHtml::hiddenField('method', $method); ?>
        <?php echo $form->errorSummary($edit); ?>

        <ul class="nav nav-tabs">
            <li class="active"><a href="#base" class="active" data-toggle="tab">上传属性</a></li>
            <li><a href="#desc" data-toggle="tab">详细描述 </a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="base">
                <?php
                $this->renderPartial('_base', array(
                    'model' => $models[0],
                    'property' => $property,
                    'itemProps' => $itemProps,
                    'edit' => $edit))
                ?>
            </div>
            <div class="tab-pane" id="desc">
                <?php
                $this->renderPartial('_desc', array('model' => $models[0], 'property' => $property,));
                ?>
            </div>
        </div>


        <?php $this->endWidget(); ?>
    </div>
            <div class="clearfix"></div>
           </div>
</div>
    </div>

<?php $this->widget('widgets.WGotop.WGotop'); ?>

<script type="text/javascript">
    $(function() {
        //自动提示
        $('#productSubTitle').artTxtCount($('.arttxt'), 60);
    });
</script>