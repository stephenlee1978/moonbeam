<div class="row-fluid">
    <div class="span12">
        <div id="myCarousel" class="carousel">
            <div class="carousel-inner">
                <?php foreach ($blob as $key=>$obj): ?>
                <?php
                if($key == 0) 
                    $itemclass = 'active item';
                else 
                    $itemclass = 'item';
                ?>
                <div class="<?php echo $itemclass; ?>">
                    <?php echo CHtml::image($obj->getImage(), '', array('width' => 940, 'height' => 500)); ?>
                    <div class="carousel-caption">
                        <h4><?php echo $obj->title; ?></h4>
                        <p>
                            <?php echo $obj->content; ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
        </div>
    </div>
</div>