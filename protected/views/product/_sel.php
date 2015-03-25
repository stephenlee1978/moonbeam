<?php
/* @var $this ProductController */
/* @var $data Product */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/bootstrap-tooltip.js', CClientScript::POS_HEAD);
?>
<script>
    jQuery(document).ready(function($) {

        $('.thumbnail').live('mouseover', function() {
            $(this).tooltip('show');
        })
    });
</script>

<li class="span3">
    <div class="thumbnail" style="height:300px;" rel="tooltip" data-original-title="<?php echo '更新时间:' . $data['updateTime']; ?>">
        <?php echo $model->showUploadTag($data['uploadTime']); ?>
        <a href=<?php echo $model->getProductLink($data['id']); ?> target="_blank">
            <?php echo $model->showLoadProductFirstImage($data['id']); ?></a>
        <div class="caption">
            <h5 class="productTitle"><a href=<?php echo $model->getProductLink($data['id']); ?> target="_blank">
                    <?php echo CHtml::encode($data['brandName']); ?><?php echo CHtml::encode($data['productTitle']); ?></a></h5>
            <p class='productPrice'>
                <em title="<?php echo '原货币为: '.$data['unit']; ?>">
                    <b><?php echo '￥'; ?></b>
                    <?php echo  $data['realPrice']; ?>
                </em>
                <del><?php echo '￥'. $data['realOriginalPrice']; ?></del>
            </p>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <a class="btn"><input type="checkbox" name="selid[]" class="checkItem"></input></a>
                </div>
                <div class="btn-group">
                    <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">操作<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0)">上传</a></li>
                        <li><a href="javascript:void(0)">上传价格/货存</a></li>
                        <li><a href="javascript:void(0)">重新采集</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</li>