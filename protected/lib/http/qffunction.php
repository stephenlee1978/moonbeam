<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function tempfile($prefix) {
    return tempnam(sys_get_temp_dir(), $prefix);
}

function findSubString($content, $sStr, $eStr) {
    $start = strpos($content, $sStr);
    if ($start === false) {
        return false;
    }

    $end_pos = strpos($content, $eStr, $start);
    if ($end_pos === false) {
        return false;
    }

    $start_pos = $start + strlen($sStr);
    return substr($content, $start_pos, $end_pos - $start_pos);
}

function getImageName($imgAddr) {
    $path_parts = pathinfo($imgAddr);
    if (isset($path_parts['basename']))
        return $path_parts['basename'];
    return null;
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

function getNow() {
    return date('Y-m-d H:i:s');
}

function trimLink($str) {
    return preg_replace("#<a[^>]*>(.*?)</a>#is", '', $str);
}

function trimButton($str) {
    return preg_replace("#<button[^>]*>(.*?)</button>#is", '', $str);
}

function analyPrice($price) {
    $price = str_replace('$', '', $price);
    $price = str_replace(',', '', $price);
    $price = trim($price);
    $reg = '/(\d{1,99}(\.\d+)?)/is';
    preg_match_all($reg, $price, $result);
    if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0])) {
        return $result[1][0];
    }
    return 0;
}

?>
