<div class="row-fluid">
    <div class="span12">
        <ul class="thumbnails">
<?php foreach ($blob as $obj): ?>
            <li class="span4" >
                <div class="thumbnail" style="height:340px;">
                    <?php echo CHtml::image($obj->getImage(), '', array('width' => 260, 'height' => 180)); ?>
                    <div class="caption">
                        <h5><?php echo $obj->title; ?></h5>
                        <p><?php echo $obj->content; ?></p>
                        <p></p>
                    </div>
                </div>
            </li>
<?php endforeach; ?>
            </ul>
    </div>
</div>

