<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '修改运费-' . Yii::app()->name;

?>

<h1>修改运费</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>