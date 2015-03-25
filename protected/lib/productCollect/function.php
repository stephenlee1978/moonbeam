<?php
include_once('simple_html_dom.php');
/* * ***********************************************
  功能函数
  v1.1
  2013‎年‎7‎月‎18‎日
 * ********************************************** */

function getOrgId($sign, $pid) {
    return str_replace($sign, '', $pid);
}

//消息通知
function message($msg) {

    echo '<br/>' . $msg;
    echo str_pad('', 256);
    ob_flush();
    flush();
}

function msgArray($msg, $array) {

    echo '<br/>' . $msg;
    echo str_pad('', 256);
    var_dump($array);
    ob_flush();
    flush();
}

//得到网站cookie
function cookie_info($home, $requrl, $savePath, $param) {
    $ch = curl_init($home);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $savePath);
    $reps = curl_exec($ch);
    curl_close($ch);
    unset($ch);

    $ch = curl_init(); //初始化
    curl_setopt($ch, CURLOPT_URL, $requrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $savePath);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $savePath);
    $reps = curl_exec($ch);
    curl_close($ch);
    unset($ch);
}

//通过curl_cookie抓取
function curl_cookie_url($url, $cookie) {

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    $response = curl_exec($ch);
    curl_close($ch);
    unset($ch);

    return $response;
}

//通过curl_cookie抓取
function curl_post_cookie_url($url, $cookie, $params) {

    $ch = curl_init();
    try {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        $response = curl_exec($ch);
    } catch (Exception $ex) {
        echo 'curl_post_cookie_url 异常';
    }

    if ($response === false) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}

function releasObj($obj) {
    unset($obj);
    $obj = null;
}

function getHtmlDomAtCookiePost($url, $cookie, $params) {
    $html = false;

    $reps = curl_post_cookie_url($url, $cookie, $params);

    if ($reps !== false) {
        @$html = json_decode($reps);
        //print_r(@$html);
        @$html = str_get_html($html->content);
    } else {
        echo '<br>curl_cookie_url失败' . $url;
    }

    return $html;
}

function getHtmlDomAtCookie($url, $cookie) {
    $html = false;

    $reps = curl_cookie_url($url, $cookie);
    if ($reps !== false) {
        @$html = str_get_html($reps);
    }

    return $html;
}

function getId($sign) {
    return uniqid($sign);
}

function getImageName($imgAddr) {
    $path_parts = pathinfo($imgAddr);
    return $path_parts['basename'];
}

function curl_url($url) {

    $ch = curl_init();

    $options = array(
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CONNECTTIMEOUT => 5,
    );
    curl_setopt_array($ch, $options);

    $response = curl_exec($ch);
    curl_close($ch);
    unset($ch);

    return $response;
}

function numInString($string) {

    $string = str_replace(',', '', $string);
    $reg = '/(\d{1,99}(\.\d+)?)/is';
    preg_match_all($reg, $string, $result);
    if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0])) {
        return $result[1][0];
    }
    return 0;
}

function getHtmlDom($url) {
    $html = false;

    $reps = curl_url($url);
    if ($reps) {
        $html = str_get_html($reps);
    }
    unset($reps);
    return $html;
}

//下载图片
function downImage($links) {
    $bRet = false;

    if (!isset($links{0}))
        return $bRet;

    $savename = 'product/' . getImageName($links);
    message('图片:' . $links);
    if (!file_exists($savename) || filesize($savename) <= 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $links);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $img_data = curl_exec($ch);


        if ($img_data === FALSE) {
            message('下载错误:' . curl_error($ch));
        } else {
            $fp = fopen($savename, 'w');
            if (fwrite($fp, $img_data) !== false)
                $bRet = true;
            else
                echo 'fwrite错误';
            fclose($fp);
        }
        curl_close($ch);
    }else {
        $bRet = true;
        message('已经下载过图片:');
    }

    return $bRet;
}

//压缩图片
function resizeJpg($srcFile, $toW, $toH) {
    $info = '';
    $data = getimagesize($srcFile, $info);
    if (!$data)
        return false;

    $im = 0;
    switch ($data[2]) { //1-GIF，2-JPG，3-PNG  
        case 1:
            if (!function_exists("imagecreatefromgif")) {
                return false;
            }
            $im = imagecreatefromgif($srcFile);
            break;

        case 2:
            if (!function_exists("imagecreatefromjpeg")) {
                return false;
            }
            $im = imagecreatefromjpeg($srcFile);
            break;

        case 3:
            $im = imagecreatefrompng($srcFile);
            break;
    }

    //计算缩略图的宽高  
    $srcW = imagesx($im);
    $srcH = imagesy($im);
    $toWH = $toW / $toH;
    $srcWH = $srcW / $srcH;
    if ($toWH <= $srcWH) {
        $ftoW = $toW;
        $ftoH = (int) ($ftoW * ($srcH / $srcW));
    } else {
        $ftoH = $toH;
        $ftoW = (int) ($ftoH * ($srcW / $srcH));
    }

    if (function_exists("imagecreatetruecolor")) {
        $ni = imagecreatetruecolor($ftoW, $ftoH); //新建一个真彩色图像  
        if ($ni) {
            //重采样拷贝部分图像并调整大小 可保持较好的清晰度  
            imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
        } else {
            //拷贝部分图像并调整大小  
            $ni = imagecreate($ftoW, $ftoH);
            imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
        }
    } else {
        $ni = imagecreate($ftoW, $ftoH);
        imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
    }

    //保存到文件 统一为.png格式  
    imagepng($ni, $srcFile); //以 PNG 格式将图像输出到浏览器或文件  
    ImageDestroy($ni);
    ImageDestroy($im);
    return true;
}

?>