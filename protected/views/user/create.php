<?php
/* @var $this ProductController */

$this->pageTitle = '新建用户-'.Yii::app()->name;


?>

<h1>新建用户</h1>
<div>
   <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>
