<?php
include_once('simple_html_dom.php');
/**
 * Description of HttpClient
 *
 * @author Administrator
 */
class HttpClient {

    public static function curl_cookie_url($url, $cookie){
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
            $response = curl_exec($ch);
            checkCurl($ch);
            curl_close($ch);
            unset($ch);

            return $response;
    }
    
    //得到网站cookie
    public static function save_cookie_file($home, $requrl, $savePath, $param) {
        $curl = curl_init($home);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $savePath);
        $response =  curl_exec($curl);
        checkCurl($curl);
        curl_close($curl);
        unset($curl);

        if($response === false) return false;
        unset($response);

        $curl1 = curl_init(); 
        curl_setopt($curl1, CURLOPT_URL, $requrl);
        curl_setopt($curl1, CURLOPT_POST, 1);
        curl_setopt($curl1, CURLOPT_POSTFIELDS, $param);
        curl_setopt($curl1, CURLOPT_HEADER, 0);
        curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl1, CURLOPT_COOKIEFILE, $savePath);
        curl_setopt($curl1, CURLOPT_COOKIEJAR, $savePath);
        curl_exec($curl1);
        checkCurl($curl);
        curl_close($curl1);
        unset($curl1);
        
        return $response !== false;
    }

    //检测curl情况
    protected static function checkCurl($hCurl) {
        if (curl_errno($hCurl)) {
            throw new Exception(curl_error($hCurl), 0);
        } else {
            $httpStatusCode = curl_getinfo($hCurl, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception(curl_error($hCurl), $httpStatusCode);
            }
        }
    }
    
     //返回采集原始内容
    public static function curl_url($url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $response = curl_exec($ch);
            curl_errno($ch);
            curl_close($ch);
            unset($ch);
            
            return $response;
    }
    
    //据网页内容返回DOM
    public static function getDomByHtml($html){ 
        return str_get_html($html);
    }
    
    //根据cookie file 得到DOM对象
    public static function getHtmlDomAtCookie($url, $cookie_file){   
        $html = false;

            $reps = curl_cookie_url($url, $cookie_file);
            if($reps !== false){
                    @$html = str_get_html($reps);
            }

            return $html;
    }
    
    public static function getHtmlDom($url){
	$html = false;
	
	$reps = HttpClient::curl_url($url);
	if($reps!==false){
		$html = str_get_html($reps);
	}
	unset($reps);
	return $html;
}

    //下载图片
    public static function downImage($links){
            $bRet = false;

            if(!isset($links{0})) return $bRet;

            $savename = dirname(Yii::app()->basePath) . Yii::app()->params['product_pic_path'].  ProductHelp::getImageName($links);
            //message('下载图片:'.$links);
            if(!file_exists($savename) || filesize($savename)<=0){
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $links);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);
                    $img_data = curl_exec($ch); 
                    curl_close($ch);

                    if($img_data!==false){
                            $fp = fopen($savename,'w');
                            if(fwrite($fp, $img_data) !== false)
                                    $bRet = true;
                            fclose($fp);
                    }
            }else{
                    $bRet = true;
            }

            return $bRet;
    }

}

?>
