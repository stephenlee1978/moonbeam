<?php
/* @var $this CityController */
$this->pageTitle = '交易管理-' . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tab.js', CClientScript::POS_HEAD);
?>
<script>
//导出excel
    $('#export_upload_btn').live('click', function() {
        
        var url = "<?php echo $this->createUrl('trade/export', array('cmd'=>'uploads')); ?>";
        ajaxExport(url);
    });
    
    $('#export_oreder_btn').live('click', function() {
        var url = "<?php echo $this->createUrl('trade/export', array('cmd'=>'statistics')); ?>";
        ajaxExport(url);
    });
    
    function ajaxExport(url){
        var data = $('#trade_form').serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data:data,
            dataType: 'json',
            async: true,
            success: function(data) {
                
                    if(data.success==true)
                        window.location = "<?php echo $this->createUrl('exportFile'); ?>";
                    else
                        alert('输出错误，请检查设置参数！');
            }
        });
    }
</script>
<h1>交易统计 <small>交易成功时间:<?php echo $model->beginTime . '-'. $model->endTime;?></small></h1>

<?php $this->renderPartial('_usearch', array('model' => $model)) ?>
<div class="well">
    <span class="label label-info">上传数量:</span><span class="badge badge-warning"><?php echo $model->getStatisticsCount(); ?></span>
    <span class="label label-success">上传收费:<?php echo $model->getStatisticsCost(); ?></span>
    <span class="label label-info">交易数量:</span><span class="badge badge-warning"><?php echo $model->getUploadsCount(); ?></span>
    <span class="label label-success">交易收费:<?php echo $model->getUploadsCost(); ?></span>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#statistics" data-toggle="tab">交易列表 <span class="badge badge-warning"><?php echo $model->getStatisticsCount(); ?></span></a></li>
    <li><a href="#uploads" data-toggle="tab">上传列表 <span class="badge badge-warning"><?php echo $model->getUploadsCount(); ?></span></a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="statistics">
        <?php $this->renderPartial('_statistics', array('model' => $model)) ?>
    </div>
    <div class="tab-pane" id="uploads">
        <?php $this->renderPartial('_uploads', array('model' => $model)) ?>
    </div>
</div>