<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '新建城市-' . Yii::app()->name;

?>

<h1>新建城市</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
