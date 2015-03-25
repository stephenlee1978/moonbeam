<?php
$addition=require('./protected/config/addition.php');
if (defined('YII_DEBUG') && YII_DEBUG === true) {
    $beta=require('./protected/config/beta.php');
    return CMap::mergeArray($beta, $addition);
}else{
    $rc=require('./protected/config/rc.php');
    return CMap::mergeArray($rc, $addition);
}
