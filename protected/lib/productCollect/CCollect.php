<?php
include_once('phpQuery.php');
include_once('simple_html_dom.php');
/**
 * Description of FFCollect
 * 采集抽象类
 * 2014/12/1
 * @author stephenlee
 */
abstract class CCollect {

    const MAX_P = 200;

    protected $home;
//网页原始内容
    protected $content;
//DOM
    protected $dom;
//是否进行跟踪
    protected $bTrace = false;
//是否关联采集
    protected $bFindAlternativeProduct = false;
//支持采集网点
    protected $citys = array();
//保存商品信息
    protected $info = array();

//构造函数
    function __construct($trace = false, $bFindAlternativeProduct = false) {
        $this->bFindAlternativeProduct = $bFindAlternativeProduct;
        $this->bTrace = $trace;
        $this->info['stock'] = 0;
        $this->init();
    }

//销毁函数
    function __destruct() {
        $this->clear();
        unset($this->citys);
        unset($this->info);
    }

    public function saveProductLinks($links) {
        if (is_array($links)) {
            foreach ($links as $link) {
                $this->saveSingleLink($link);
            }
            $this->trace('共收集集商品:'.count($links));
        }
    }

    private function saveSingleLink($url) {
        try {
            ProductLink::saveProductLink($url);
        } catch (CException $ex) {
        }
    }

    public function __set($name, $value) {
        $this->setAttribute($name, $value);
    }

    public function setAttribute($name, $value) {
        if (property_exists($this, $name))
            $this->$name = $value;
        else
            $this->info[$name] = $value;

        return true;
    }

    public function __isset($name) {
        if (property_exists($this, $name)) {
            return true;
        } elseif (isset($this->info[$name]))
            return true;
        return false;
    }

    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        } elseif (isset($this->info[$name]))
            return $this->info[$name];
        return (null);
    }

    protected function clear() {
        if (isset($this->dom)) {
            $this->dom->clear();
            unset($this->dom);
        }
    }

    //压缩图片
    protected function resizeJpg($srcFile, $toW, $toH) {
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

//下载图片
    protected function downImage($links) {
        $bRet = false;

        if (!isset($links{0}))
            return $bRet;
        if (!$this->getInfo('id'))
            return $bRet;

        $savename = dirname(Yii::app()->basePath) . '/product/' . $this->info['id'];
        if (!is_dir($savename)) {
            if (!mkdir($savename, 0777))
                throw new CException('创建目录失败');
            @chmod($savename, 0777);
        }

        Yii::import('lib.functions');
        $savename .= '/' . Functions::getImageName($links);
//message('下载图片:'.$links);
        if (!file_exists($savename) || filesize($savename) <= 0) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $links);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $img_data = curl_exec($ch);
            curl_close($ch);
            if ($img_data !== false) {
                @chmod($savename, 0666);

                $fp = fopen($savename, 'w');
                if ($fp !== false && fwrite($fp, $img_data) !== false) {
                    $bRet = true;
                }

                fclose($fp);

                if (filesize($savename) > 500 * 1024)
                    $this->resizeJpg($savename, 336, 596);
            }else {
                //Yii::log('img_data error');
            }
        } else {
            $bRet = true;
        }

        return $bRet;
    }

    protected function getHtmlDom($url, $cookie = '') {
        if (!isset($url{0}))
            return false;

        $content = $this->curl_url($url, $cookie);
        if ($content === false)
            return false;
        
        //$this->trace($content);

        return str_get_html($content);
    }

//使用内部
    protected function getInterHtmlDom($url, $cookie = '') {
        if (!isset($url{0}))
            return false;

        $this->content = $this->curl_url($url, $cookie);
        if ($this->content === false)
            return false;

        //$this->trace($this->content);
        return $this->getDom();
    }
    
    //使用内部
    protected function getInterHtmlDomAgent($url, $cookie = '') {
        if (!isset($url{0}))
            return false;

        $this->content = $this->curl_url_agent($url, $cookie);
        if ($this->content === false)
            return false;
        //$this->trace($this->content);
        return $this->getDom();
    }

    protected function analyPrice($price) {
        $price = str_replace('$', '', $price);
        $price = str_replace(',', '', $price);
        $reg = '/(\d{1,99}(\.\d+)?)/is';
        preg_match_all($reg, $price, $result);
        if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0])) {
            return $result[1][0];
        }
        return 0;
    }

    protected function filterLink($content) {
        return preg_replace("#<a[^>]*>(.*?)</a>#is", '', $content);
    }

    protected function setDom($dom) {
        $this->clear();
        $this->dom = $dom;
    }

    protected function getHtmlContent($cookie = '') {
        if (!$this->getInfo('url'))
            return false;

        $this->content = $this->curl_url($this->info['url'], $cookie);
        if ($this->content === false)
            return false;

        return true;
    }

    protected function getDom($content = '') {
        if (isset($this->content{0})) {
            $this->dom = str_get_html($this->content);
            return true;
        }
        return false;
    }

    protected function getDomFromContent($content) {
        if (isset($content{0})) {
            return str_get_html($content);
        }
        return false;
    }

//初始化
    abstract protected function init();

    protected function numInString($string) {

        $string = str_replace(',', '', $string);
        $reg = '/(\d{1,99}(\.\d+)?)/is';
        $result = NULL;
        preg_match_all($reg, $string, $result);
        if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0])) {
            return $result[1][0];
        }
        return false;
    }

    protected function trimLink($str) {
        return preg_replace("#<a[^>]*>(.*?)</a>#is", '', $str);
    }

    protected function getJosn($url) {
        $resp = $this->curl_url($url);
        if (!$resp)
            return false;

        return json_decode($resp);
    }

    protected function curl_cookie($url, $cookie) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        
        $reps = curl_exec($ch);
        
        curl_close($ch);
        unset($ch);
        return $reps;
    }

    protected function curl_get_cookie($url, $cookie = '') {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (isset($cookie{0})) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        if($response===false)
            $this->trace('curl_error:'.curl_error($ch));
        
        curl_close($ch);

        //$this->trace($response);
        return $response;
    }
    
    protected function curl_post_cookie($url, $cookie, $param) {
        $ch = curl_init(); //初始化
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        if(isset($param{0})){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $reps = curl_exec($ch);
        if($reps === false){
            $this->trace(curl_error($ch));
        }

        curl_close($ch);
        unset($ch);

        return $reps;
    }
    
    protected function curl_url($url, $cookie = '') {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (isset($cookie{0})) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        }
        if (strpos($url, "https")!==false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        if($response===false)
            $this->trace('curl_error:'.curl_error($ch));
        curl_close($ch);

        //$this->trace($response);
        return $response;
    }
    
    protected function curl_url_agent($url, $cookie = '') {

        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (isset($cookie{0})) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        if($response===false)
            $this->trace('curl_error:'.curl_error($ch));
        curl_close($ch);

        //$this->trace($response);
        return $response;
    }
    

//解析单个商品
    public function productAnaly($url, $city = '') {
        $this->info['url'] = $url;

        $this->trace('开始采集商品URL:' . $this->info['url']);

        $this->setCity($city);
    }

//翻页采集
    abstract function collectPage($url, $city = '');

    abstract function collectSinglePage($url, $city = '');

//计算价格
    protected function countPrice($price) {

        return Functions::math($price, City::getMath($this->curCity));
    }

//保存信息
    protected function saveInfo() {
        if (!$this->getInfo('pid'))
            return false;
        
        Yii::import('lib.functions');
        $id = $this->info['id'];

        $product = Product::model()->findByPk($id);
        if ($product === null && $this->info['stock'] == 0) {//新货，但无货存
            $this->trace('新建商品无库不进行入库！');
            return false;
        } elseif ($product === null) {
            $product = new Product;
            $product->id = $id;
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {


            if ($this->info['stock'] != 0) {
                $this->getPercentOff();

                if ($this->getInfo('pid'))
                    $product->pid = $this->info['pid'];

                if ($this->getInfo('brandName'))
                    $product->brandName = CHtml::decode($this->info['brandName']);

                if ($this->getInfo('productTitle'))
                    $product->productTitle = CHtml::decode($this->info['productTitle']);

                if ($this->getInfo('originalRetailPrice'))
                    $product->originalPrice = trim($this->info['originalRetailPrice']);

                if ($this->getInfo('price'))
                    $product->price = trim($this->info['price']);

                if ($this->getInfo('percentOff'))
                    $product->percentOff = $this->info['percentOff'];

                if ($this->getInfo('desc'))
                    $product->desc = $this->info['desc'];

                if ($this->getInfo('details'))
                    $product->details = $this->info['details'];

                if ($this->getInfo('designer'))
                    $product->designer = $this->info['designer'];

                if ($this->getInfo('sizeFitContainer'))
                    $product->sizeFitContainer = $this->info['sizeFitContainer'];

                if ($this->getInfo('unit'))
                    $product->unit = $this->info['unit'];

                if ($this->getInfo('url'))
                    $product->url = $this->info['url'];
                
                if ($this->getInfo('area'))
                    $product->area = $this->info['area'];

                if ($this->getInfo('sizes')) {
                    $product->saveSizeAttri($this->info['sizes']);
                }

                if ($this->getInfo('alternative')) {
                    $product->alternative = $this->info['alternative'];
                }

                if ($this->getInfo('colors'))
                    $product->saveColorAttri($this->info['colors']);

                if ($this->getInfo('sku'))
                    $product->saveSku($this->info['sku']);

                if ($this->getInfo('images'))
                    $product->saveProductImages($this->info['images']);

                $product->station = $this->station;

                $product->city = $this->info['city'];
            }

            if ($this->getInfo('stock'))
                $product->stock = $this->info['stock'];

            $product->updateTime = Functions::getNow();


            if (!$product->save()) {
                $this->trace('商品入库失败:');
                $this->trace($product->getErrors());
                return false;
            } else {
                $transaction->commit();
                $this->trace('商品抓取成功。');
            }
        } catch (Exception $e) {
            $transaction->rollback();
            $this->trace('商品入库失败:' . $e->getMessage());
            return false;
        }

        $astr = CHtml::normalizeUrl(array('product/view/id/' . $id));
        $this->trace('<a href=' . $astr . ' target=\'_blank\' >查看商品</a>');

        unset($product);

        return $id;
    }

    public function updateUpload($id) {
        $num_iid = Uploadhistory::fideNumIid($id);
        if ($num_iid === false)
            return;

        $uploadInfo = new uploadInfo;
        if ($uploadInfo->find($id) === false) {
            Functions::message('找不到上传商品信息，不进行更新!');
            unset($uploadInfo);
            return false;
        }

        Yii::import('lib.Taobao');
        Taobao::updateProduct($uploadInfo, $num_iid);

        unset($uploadInfo);
    }

//打折
    private function getPercentOff() {
        $originalRetailPrice = $this->getInfo('originalRetailPrice');
        $price = $this->getInfo('price');
        if ($originalRetailPrice && $price && $originalRetailPrice != $price) {
            $this->info['percentOff'] = intval($price / $originalRetailPrice * 100);
            $this->trace('打折: ' . (string) $this->info['percentOff']);
        }
    }

//得到商品信息
    protected function getInfo($desc) {
        if (array_key_exists($desc, $this->info))
            return $this->info[$desc];
        return false;
    }

//可否进行采集
    protected function setCity($city) {

        $key = array_search($city, $this->citys, true);
        try {
            if ($key !== false) {
                $this->info['city'] = $this->citys[$key];
            } else {
                $this->info['city'] = $this->citys[0];
            }
            $this->trace('抓取区域:' . $this->info['city']);
        } catch (Exception $exc) {
            throw new CException('获取采集站点异常！');
        }
    }

//跟踪消息
    protected function trace($msg) {
        if (!$this->bTrace)
            return;

        $message = '';
        if (is_array($msg) || is_object($msg))
            $message = CVarDumper::dumpAsString($msg);
        else {
            $message = $msg;
        }

        $this->message($message);
    }

    //消息通知
    private function message($msg) {
        echo '<br/>' . $msg;
        echo str_pad('', 4096);
        ob_flush();
        flush();
    }

}
