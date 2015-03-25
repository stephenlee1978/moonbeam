<?php
/* @var $this ProductController */
/* @var $data data */
Yii::import('application.extensions.phpexcel.JPhpExcel');
$xls = new JPhpExcel('UTF-8', false, 'My Test Sheet');
$xls->addArray($data);
$xls->generateXML('my-test');
		
unset($xls);


?>

