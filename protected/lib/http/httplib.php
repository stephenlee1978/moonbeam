<?php
include('trace.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('LOAD_HTML_TIMEOUT', 60);

function loadHtml($url, $cookie = '') {
    if (!isset($url{0}))
        return false;

    $html = curl_url($url, $cookie);

    return $html;
}

function getJosn($url) {
    $resp = curl_url($url);
    if (!$resp)
        return false;

    return json_decode($resp);
}

function postJosn($url,$cookie,$param) {
    $resp = curl_post_cookie($url, $cookie, $param);
    if ($resp===false)
        return false;

    return json_decode($resp);
}

function curl_url($url, $cookie = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if (isset($cookie{0})) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    }

    if (strpos($url, "https") !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, LOAD_HTML_TIMEOUT);

    $response = curl_exec($ch);
    if ($response === false)
        trace('curl_error:' . curl_error($ch));
    curl_close($ch);

    return $response;
}

function curl_get_302($url, $cookie = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
        if(isset($cookie{0})){
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        }
        $data = curl_exec($ch);
        $Headers = curl_getinfo($ch);
        curl_close($ch);
        if ($data&&$Headers)
            return $Headers["url"];
        else
            return false;
}

function curl_get_cookie($url, $cookie = '',$referer='') {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    if(isset($cookie{0})){
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, LOAD_HTML_TIMEOUT);
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    if(isset($referer{0}))
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    //curl_setopt($ch,CURLOPT_FOLLOWLOCATION,false);
    
    if (strpos($url, "https") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
     }

    $response = curl_exec($ch);
    if ($response === false)
        trace('curl_error:' . curl_error($ch));
    
    //trace($response);
    curl_close($ch);

    return $response;
}

function curl_post_cookie($url, $cookie, $fields,$referer='') {
    $ch = curl_init(); //初始化
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);

    $fields_string='';
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string, '&');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    
    if (strpos($url, "https") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
     }
     
    if(isset($referer{0})){
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    }else{
        curl_setopt($ch, CURLOPT_HEADER, 0);
    }
    
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_TIMEOUT, LOAD_HTML_TIMEOUT);
    $reps = curl_exec($ch);
    if ($reps === false) {
        trace(curl_error($ch));
    }
    trace($reps);
    curl_close($ch);
    unset($ch);

    return $reps;
}

?>
