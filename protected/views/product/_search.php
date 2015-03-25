<script type="text/javascript">
    $(function() {
        $(window).scroll(function() {

            if ($(window).scrollTop() > 100) {
                $('.jumbotron').find('.subnav').addClass('subnav-fixed');
            } else {
                $('.jumbotron').find('.subnav').removeClass('subnav-fixed');
            }
        })

        $("#upload_btn").live('click', function() {
            $("#plist_form").attr('action', '<?php echo Yii::app()->createUrl('taobao/edit'); ?>');
            $("#plist_form").submit();
        });

        $("#collect_btn").live('click', function() {
            $("#plist_form").attr('action', '<?php echo Yii::app()->createUrl('taobao/collect'); ?>');
            $("#plist_form").submit();
        });
    });
</script> 

<div id="overview" class="jumbotron subhead">
    <div class="subnav search_well row-fluid">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'psearch_form',
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'GET',
            'htmlOptions' => array('class' => 'form-inline')
        ));
        ?>

        <div class="container_content">
            <div class="row-fluid">
            <div class="span-2"><?php echo $form->dropDownList($model, 'site', CHtml::listData(Station::model()->findAll(), "station", "station"), array('class' => 'input-medium', 'empty' => '全部站点')); ?></div>              
            <div class="span-2"><?php echo $form->textField($model, 'pinfo', array('class' => 'input-medium', 'placeholder' => '商品信息查询')); ?></div>           
                <div class="span-2"><?php echo $form->dropDownList($model, 'uploaded', array('0' => '未上传', '1' => '已上传'), array('class' => 'input-small', 'empty' => '全部状态',)); ?></div>    
                <div class="span-2"><?php echo $form->dropDownList($model, 'uploadtime', array('0' => '今天', '1' => '三天内', '2' => '本周内', '3' => '一个月内', '4' => '三个月内'), array('empty' => '上传时间', 'class' => 'input-small')); ?></div>            
                <div class="span-2"><?php echo $form->dropDownList($model, 'orderuploadtime', array('0' => '更新时间升序', '1' => '更新时间降序', '2' => '上传时间升序', '3' => '上传时间降序'), array('empty' => '时间排序', 'class' => 'input-medium')); ?></div>
                <div class="span-2"><?php echo $form->textField($model, 'pricerangb', array('class' => 'input-small', 'placeholder' => '最小价格')); ?></div>
                <div class="span-2"><?php echo $form->textField($model, 'pricerange', array('class' => 'input-small', 'placeholder' => '最大价格')); ?></div>
            </div>

            <div class="row-fluid">
                <div class="span-2"><span>
                <input type="checkbox" class="checkAll"/>
                <label class="ckk_lable">全选</label></span></div>
                <div class="span-2"><?php echo CHtml::submitButton('搜索商品', array('class' => 'qf_btn')); ?></div>
                <div class="span-2"><?php echo CHtml::Button('批量上传', array('class' => 'qf_btn', 'id' => 'upload_btn')); ?></div>
                <div class="span-2"><?php echo CHtml::Button('批量更新', array('class' => 'qf_btn', 'id' => 'collect_btn')); ?></div>
            </div>
              </div>
            <?php $this->endWidget(); ?>
        </div>
</div>