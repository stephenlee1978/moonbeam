<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '新建货币-' . Yii::app()->name;

?>

<h1>新建货币</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
