<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '修改收费-' . Yii::app()->name;

?>

<h1>修改收费</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>