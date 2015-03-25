<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '新建站点-' . Yii::app()->name;

?>

<h1>新建站点</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
