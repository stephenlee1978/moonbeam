<?php
/* @var $this UserController */
/* @var $model User */

$this->pageTitle='用户修改';
?>

<h2><?php echo $this->pageTitle; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>