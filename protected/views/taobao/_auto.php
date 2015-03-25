<div class="span4" style="overflow: hidden;text-overflow: ellipsis;white-space:nowrap;">
    <a class="btn"><label class="checkbox" style="width: auto;">全选
            <?php echo CHtml::checkBox('selall', true, array('class' => 'checkAll')) ?>
        </label></a>

    <ul class="thumbnails">
        <?php foreach ($models as $model) { ?>
            <li><div class="thumbnail">
                    <a class="btn" href="<?php echo CHtml::normalizeUrl(array('product/view/id/' . $model->id)); ?>" target="_blank">        
                        <?php echo $model->getFirstImg(); ?>
                    </a>
                    <p><a class="btn">
                            <?php echo CHtml::checkBox('selid[]', true, array('value' => $model->id, 'class' => 'checkItem')) ?>
                        </a></p>
            </li>
        <?php }
        ?>
    </ul>
</div>