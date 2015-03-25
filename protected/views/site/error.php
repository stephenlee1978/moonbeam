<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle='错误-'.Yii::app()->name ;
?>

<h1>错误</h1>


<div class="well row">
    <div class="span2">
            <ul class="thumbnails">
                <li class="span2">
                    <a href="javascript:void(0)" class="thumbnail">
                        <?php echo CHtml::image(Yii::app()->baseUrl . '/img/error.png', '', array('width'=>128, 'height'=>128)); ?>
                    </a>
                </li>
            </ul>
        </div>
    <div class="span9">
            <div class="alert alert-error">
                    <h4 class="alert-heading">错误码:</h4>
                    <span class="badge badge-error"><?php echo $error['code']; ?></span>
            </div>
                <div class="alert alert-info">
                        <h4 class="alert-heading">错误消息:</h4>
                    <?php echo $error['message']; ?>
                </div>
        </div>
</div>