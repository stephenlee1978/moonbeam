<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function trace($msg) {
    $message = '';
    if (is_array($msg) || is_object($msg)){
            $message = CVarDumper::dumpAsString($msg);
    }else {
            $message = $msg;
    }

    message($message);
}

//消息通知
function message($msg) {
   echo '<br/>' . $msg;
   echo str_pad('', 4096);
   ob_flush();
   flush();
}

?>
