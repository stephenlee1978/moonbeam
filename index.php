<?php
date_default_timezone_set('PRC');
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
define('YII_TRACE_LEVEL',4);

ini_set('display_errors','1');
ini_set('max_execution_time', 0);

require_once($yii);
$params=require('./protected/config/params.php');
$base=require('./protected/config/main.php');
$config=CMap::mergeArray($base, $params);
Yii::createWebApplication($config)->run();
