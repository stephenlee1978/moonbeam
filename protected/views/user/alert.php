<?php
/* @var $this UserController */
/* @var $name */

$this->pageTitle = Yii::app()->name . ' - 用户提示';
?>
<div class="row-fluid">
    <div class="page-header">
        <h1>用户提示
            <small>
                <?php echo $name; ?>
            </small
        </h1>
    </div>

    <div class="row">
        <div class="span3">
            <ul class="thumbnails">
                <li class="span3">
                    <a href="javascript:void(0)" class="thumbnail">
                        <?php echo CHtml::image(Yii::app()->baseUrl . '/img/user128.png'); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="span9">
            <?php echo '您已经成功注册，但需登陆管理员审核后才能使用，请您耐心登陆! 再次感谢您的支持!'; ?>
        </div>
    </div>
</div>

