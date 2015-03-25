<?php
/**
 * Description of ProductHelp
 *
 * @author Administrator
 */
class ProductHelp {
    //解析价格格式
    public static function analyPriceStringToNumber($price) {

        $price = str_replace(',', '', $price);
        $reg = '/(\d{1,99}(\.\d+)?)/is';
        preg_match_all($reg, $price, $result);
        if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0])) {
            return $result[1][0];
        }
        return 0;
    }
    
    public static function getImageName($imgAddr){
            $path_parts = pathinfo($imgAddr);
            return $path_parts['basename'];
    }
    
    public static function resizeJpg($srcFile, $toW, $toH){
        $info = '';   
            $data = getimagesize($srcFile, $info);  
            if (!$data)  
                    return false;  

            $im = 0;
            switch ($data[2]) //1-GIF，2-JPG，3-PNG  
            {  
            case 1:  
                    if(!function_exists("imagecreatefromgif"))  
                    {  
                            return false; 
                    }  
                    $im = imagecreatefromgif($srcFile);  
                    break;  

            case 2:  
                    if(!function_exists("imagecreatefromjpeg"))  
                    {  
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
            if ($toWH <= $srcWH)   
            {  
                    $ftoW = $toW;  
                    $ftoH = (int)($ftoW * ($srcH / $srcW));  
            }  
            else   
            {  
                    $ftoH = $toH;  
                    $ftoW = (int)($ftoH * ($srcW / $srcH));  
            } 

            if (function_exists("imagecreatetruecolor"))   
            {  
                    $ni = imagecreatetruecolor($ftoW, $ftoH); //新建一个真彩色图像  
                    if ($ni)   
                    {  
                            //重采样拷贝部分图像并调整大小 可保持较好的清晰度  
                            imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);  
                    }   
                    else   
                    {  
                            //拷贝部分图像并调整大小  
                            $ni = imagecreate($ftoW, $ftoH);  
                            imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);  
                    }  
            }  
            else   
            {  
                    $ni = imagecreate($ftoW, $ftoH);  
                    imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);  
            }  

            //保存到文件 统一为.png格式  
            imagepng($ni, $srcFile); //以 PNG 格式将图像输出到浏览器或文件  
            ImageDestroy($ni);  
            ImageDestroy($im);  
            return true;  
    }
    
    public static function getImageNameByUrl($imgAddr) {
        $path_parts = pathinfo($imgAddr);
        return $path_parts["basename"];
    }
    
    //按公式计算出价格
    private static function math($price, $math){
        $value = floatval($price);
        if(isset($math{0}) && isset($price{0})){
            $count = str_replace('value', '$value', $math);
            return strval(ceil(eval("return $count;")));
        }
        
        throw new Exception('Functions math exception!'); 

        return 0;
    }
    
    public static function trimLinkInfo($str){
        return preg_replace("#<a[^>]*>(.*?)</a>#is", '', $str);
    }
    
    //计算价格
    public static function countPrice($price){
        return ProductHelp::math($price, City::getMath($this->curCity));;
    }
}

?>
