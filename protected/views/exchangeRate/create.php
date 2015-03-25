<?php
/* @var $this RateController */
/* @var $model Rate */
$this->pageTitle = '新建汇率-' . Yii::app()->name;

?>
<?php
$this->widget('ext.flash.Flash', array(
    'keys' => 'erate_msg',
    'htmlOptions' => array('class' => 'msg'),
));
?>

<h1>新建汇率</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
