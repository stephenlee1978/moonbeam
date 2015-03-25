<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Functions
 * 20140806
 * @author stephen
 */
class Functions {

    public static function subTitle($subTitle){
        if (defined('YII_DEBUG') && YII_DEBUG === true) $subTitle = '沙箱测试' . $subTitle;
        
        $len = $len = (strlen($subTitle) + mb_strlen($subTitle, 'utf-8')) / 2;
        if ($len > 60) {
            Yii::log('subTitle:'.$subTitle);
            return mb_strimwidth($subTitle, 0, 58, '','utf-8');
        }
        return $subTitle;
    }

    public static function str_cut($str_cut,$length){

    if(strlen($str_cut) > $length){   //处理标题，太长用……表示
       for($i=0; $i < $length; $i++){
           if (ord($str_cut[$i]) > 128) $i++;
       }
       $str_cut = substr($str_cut,0,$i);
   }
   return $str_cut;
}

    public static function isOverSixTeenWord($subTitle){
        $len = (strlen($subTitle) + mb_strlen($subTitle, 'utf-8')) / 2;
        if($len > 60){
            return true;
        }
        return false;
    }

    //循环删除目录和文件函数
    public static function delDirAndFile($dirName) {
        try {
            if ($handle = @opendir("$dirName")) {
                while (false !== ($item = readdir($handle))) {
                    if ($item != "." && $item != "..") {
                        if (is_dir("$dirName/$item")) {
                            delDirAndFile("$dirName/$item");
                        } else {
                            unlink("$dirName/$item");
                        }
                    }
                }
                closedir($handle);
                rmdir($dirName);
            }
        } catch (Exception $exc) {
            
        }
    }

    public static function aMonthAgo() {
        return date("Y-m-d H:i:s", strtotime("last month"));
    }

    public static function getYearMonth() {
        return date("Y年n月");
    }

    public static function aMonthLater() {
        return date("Y-m-d H:i:s", strtotime("+1 month"));
    }

    public static function getImageName($imgAddr) {
        $path_parts = pathinfo($imgAddr);
        if (isset($path_parts['basename']))
            return $path_parts['basename'];
        return null;
    }

    public static function message($msg) {
        $message = '';
        if (is_array($msg) || is_object($msg))
            $message = CVarDumper::dumpAsString($msg);
        else {
            $message = $msg;
        }
        
        echo $message . '<br>';
        echo str_repeat('  ', 4096);
        ob_flush();
        flush();
    }

    public static function isTimeCloseHalfHour($time) {
        return (strtotime($time) - time()) <= 60 * 30;
    }
    
    public static function isTimeOut($time) {
        return (strtotime($time) - time()) >= 0;
    }

    public static function getNow() {
        return date('Y-m-d H:i:s');
    }

    public static function getNowFormat($time) {
        return date('Y-m-d H:i:s', $time);
    }

    public static function getFrstTrimester() {
        return date('Y-m-d H:i:s', strtotime("-3 month"));
    }

    //按公式计算出价格
    public static function math($value, $math) {
     Yii::log('value='.$value);
     Yii::log('math='.$math);
        try {
            $parameter = floatval($value);
            if (!is_float($parameter))
                return 0;
            if (isset($math{0}) && isset($value{0})) {
                $math = preg_replace("/{(\w+)}/", strval($parameter), $math);
                 $mathValue = eval("return $math;");
                 if($mathValue!==null && $mathValue !== false){
                    return round((float)$mathValue, 2);
                 }
            }
        } catch (Exception $exc) {
            
        }

        return round((float)$value, 2)+200;
    }

    public static function pattern($value, $pattern) {
        try {

            if (isset($pattern{0}) && isset($value{0})) {
                return preg_replace("/{(\w+)}/", $value, $pattern);
            }
        } catch (Exception $exc) {
            
        }

        return false;
    }

    //得到授权超时时间
    public static function getOutTime($expires) {
        return Functions::getNowFormat($expires + time());
    }

    //删除以分隔符排列的图片
    public static function deleteImage($image) {

        //trigger_error('image path:'.$image);
        $fullPath = Yii::getPathOfAlias('rootpath') . $image;
        Yii::log('delete image path:' . $image);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    public static function getExchangeRate($from_Currency, $to_Currency) {
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);
        $url = "download.finance.yahoo.com/d/quotes.html?s=" . $from_Currency . $to_Currency . "=X&f=sl1d1t1ba&e=.html";
        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);

        if ($rawdata === false)
            return false;

        $data = explode(',', $rawdata);
        return $data[1];
    }

}
