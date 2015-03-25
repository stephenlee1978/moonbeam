<?php
?>

<?php if ($this->beginCache('product_view' . $data['id'] . strtotime($data['uploadTime']), array('duration' => 3600))) { ?>

    <li class="customspan">
        <div class="thumbitem" style="height:265px;" >
            <div class="itemimg">
                <?php if (isset($data['uploadTime'])) { ?>
                    <div class="pick">
                        
                    </div>
                <?php } ?>

                <a href=<?php echo CHtml::normalizeUrl(array('product/view/id/' . $data['id'])); ?> target="_blank">
                    <?php
                    echo CHtml::image(Yii::app()->baseUrl . '/product/' . $data['id'] . '/' . ProductImages::getFirstImage($data['id']), '', array(
                                'width' => "160px", 'height' => "216px"));
                    ?>
                </a>
                <div class="btn_tool">
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <a class="btn btn-success"><?php echo CHtml::checkBox('selid[]', false, array('value' => $data['id'], 'class' => 'checkItem')); ?></a>
                            <?php echo CHtml::link('上传', array('taobao/edit', 'id' => $data['id']), array('id' => "edit", 'class' => 'btn btn-success')); ?>
                            <?php echo CHtml::link('更新', array('taobao/collect', 'id' => $data['id']), array('id' => "upload", 'class' => 'btn btn-success')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="caption">
                <h5 class="productTitle"><a rel="tooltip" class="titleTooltip" href=<?php echo CHtml::normalizeUrl(array('product/view/id/' . $data['id'])); ?> target="_blank" data-original-title="<?php echo $data['brandName']; ?><?php echo $data['productTitle']; ?>">
                        <?php echo $data['brandName']; ?><?php echo $data['productTitle']; ?></a></h5>
                <p class='productPrice'>
                    <em>
                        <b><?php echo $data['unit']; ?></b>
                        <?php echo $data['price']; ?>
                    </em>
                    <del><?php
                        if ($data['percentOff'] > 0 && $data['percentOff'] < 100) {
                            echo$data['unit'] . $data['originalPrice'];
                        }
                        ?></del>
                </p>
            </div>
        </div>

    </li>

    <?php
    $this->endCache();
}
?>