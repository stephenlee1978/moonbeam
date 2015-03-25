<?php if($this->beginCache('desc'.$model->id.  strtotime($model->updateTime).$model->city, array('duration'=>3600))){  ?>
<div style="max-height: 600px;
overflow: auto;">
    <h3 style="border-bottom: 1px solid rgb(229, 229, 229);" >商家说明</h3>
        <p><?php echo $model->setAddInfo();  ?></p>
<?php 
echo $model->getImagesDesc();
?>
<p><h3 style="border-bottom: 1px solid rgb(229, 229, 229);">商品介绍</h3><?php echo $model->details; ?></p>
<p><h3 style="border-bottom: 1px solid rgb(229, 229, 229);">商品信息</h3><?php echo $model->desc; ?></p>
<p><h3 style="border-bottom: 1px solid rgb(229, 229, 229);">设计师</h3><?php echo $model->designer; ?></p>
<p align='center'><?php echo $model->sizeFitContainer; ?></p>
<p>

</div>
<?php $this->endCache(); } ?>