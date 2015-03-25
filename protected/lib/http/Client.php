<?php
/**
 * Description of httpCommunication
 *
 * @author stephen
 */
class Client {
    
    public $error = array();
    public $urfheader = array(
    "content-type: application/x-www-form-urlencoded; 
    charset=UTF-8"
    );
    public $errorCode = 0;

    function __destruct() {
        unset($this->error);
    }
    
    //执行任务
    public function get($request) {
        //获取业务参数
        $apiParams = $request->getParameters();

        $requestUrl = $request->url . "?";

        try {
            $resp = $this->curl_get($requestUrl, $apiParams);
        } catch (Exception $e) {
            $this->error['code'] = $e->getCode();
            $this->error['message'] = $e->getMessage();
            return false;
        }

        $respWellFormed = false;

        $respObject = json_decode($resp);


        return $respObject;
    }

    public function post($request) {
        //获取业务参数
        $apiParams = $request->getParameters();

        $requestUrl = $request->url . "?";

        try {
            $resp = $this->curl($requestUrl, $apiParams);
        } catch (Exception $e) {
            $this->error['code'] = $e->getCode();
            $this->error['message'] = $e->getMessage();
            return false;
        }

        $respObject = json_decode($resp);

        return $respObject;
    }

    private function fitUrl($url, $getFields = null) {
        if (is_array($getFields) && 0 < count($getFields)) {
            $postBodyString = "";
            foreach ($getFields as $k => $v) {
                $postBodyString .= "$k=" . urlencode($v) . "&";
            }
            unset($k, $v);
            $url .= substr($postBodyString, 0, -1);
        }
        return $url;
    }

    //发送get请求
    public function curl_get($url, $getFields = null) {
        $ch = curl_init();

        $url .= $this->fitUrl($url, $getFields);

        curl_setopt($ch,CURLOPT_HTTPHEADER, $this->urfheader);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $reponse = curl_exec($ch);
        

        if (curl_errno($ch)) {
            $this->error['code'] = 1234;
            $this->error['message'] = curl_error($ch);
            return false;
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                $this->error['code'] = $httpStatusCode;
                $this->error['message'] = $reponse;
                return false;
            }
        }
        curl_close($ch);
        return $reponse;
    }

    //跳转
    public function redirect($request) {
        //获取业务参数
        $apiParams = $request->getParameters();

        $requestUrl = $request->url . "?";

        try {
            $requestUrl = $this->fitUrl($requestUrl, $apiParams);
            header("Location: {$requestUrl}");
        } catch (Exception $e) {
            $this->error['code'] = $e->getCode();
            $this->error['message'] = $e->getMessage();
        }
    }

    public function curl($url, $postFields = null) {
        $ch = curl_init();
        
        curl_setopt($ch,CURLOPT_HTTPHEADER, $this->urfheader);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_array($postFields) && 0 < count($postFields)) {
            $postBodyString = "";
            $postMultipart = false;
            foreach ($postFields as $k => $v) {
                if ("@" != substr($v, 0, 1)) {//判断是不是文件上传
                    $postBodyString .= "$k=" . urlencode($v) . "&";
                } else {//文件上传用multipart/form-data，否则用www-form-urlencoded
                    $postMultipart = true;
                }
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }
		
		
        $reponse = curl_exec($ch);
        $respObject = json_decode($reponse);
            if (null !== $respObject) {
                foreach ($respObject as $propKey => $propValue) {
                    $respObject = $propValue;
                }
        }
            
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
            $this->error['code'] = 56;
            $this->error['message'] = curl_error($ch);
            return false;
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                $this->error['code'] = $httpStatusCode;
                $this->error['message'] = $reponse;
            return false;
            }
        }
        curl_close($ch);
        return $reponse;
    }

}
