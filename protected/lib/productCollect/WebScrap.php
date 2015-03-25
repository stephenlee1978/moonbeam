<?php
include dirname(dirname(__FILE__)).'/http/QFHtmlParser.php';
include dirname(dirname(__FILE__)).'/http/qffunction.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebScrap
 *
 * @author fengfeng
 */
abstract class WebScrap {
    //跟踪
    public $bTrace = true;
    //cookie_file
    protected $cookie_file;

    protected $relevanceProduct;
    //是否关联采集
    protected $bFindAlternativeProduct = false;
    //DOM
    protected $htmlParser;
    //支持采集网点
    protected $citys;
    
    /////////商品信息/////////
    //station
    protected $station;
    //存貨
    protected $isInStock = false;
    //存貨
    protected $stock = 0;
    //URL
    protected $url = '';
    //unit
    protected $unit;
    //city
    protected $city;
     //pid
    protected $pid;
     //id
    protected $id;
    //sizes
    protected $sizes;
    //colors
    protected $colors;
    //images
    protected $images;
    //listPrice
    protected $listPrice=0;
    //sellingPrice
    protected $sellingPrice=0;
    //brandName
    protected $brandName;
    //productTitle
    protected $productTitle;
    //sku
    protected $sku;
    //percentOff
    protected $percentOff;
    //tags
    protected $tags;
    //details
    protected $details;
    //designer
    protected $designer;
    //sizeFitContainer
    protected $sizeFitContainer;
    //desc
    protected $desc;
    //area
    protected $area;

    //构造函数
    function __construct($bFindAlternativeProduct = false) {
        $this->htmlParser = new QFHtmlParser();
        $this->bFindAlternativeProduct = $bFindAlternativeProduct;
        $this->stock = 0;
        $this->init();
    }
    
    function __destruct() {
        $this->clear();
        unset($this->relevanceProduct);
        unset($this->sizeFitContainer);
        unset($this->citys);
        unset($this->images);
        unset($this->sizes);
        unset($this->sku);
    }
    
    //跟踪
    protected function trace($message) {
        if ($this->bTrace) trace($message);
    }


    //初始化
    abstract protected function init();
    
    protected function clear() {
        if (isset($this->htmlParser)) {
            unset($this->htmlParser);
        }
    }
    
    protected function getCookieFile(){
        $this->cookie_file = tempfile('cok');
    }
    
    //解析单个商品
    public function productAnaly($url, $city = '') {
        $this->url = $url;

        $this->trace('开始采集商品URL:' . $url);
        
        if(isset($city{0})<=0 && is_array($this->citys) ){
            list($key, $val) = each($this->citys);
            $this->unit = $val;
            $this->city = $key;
            return true;
        }else if(is_array($this->citys) && array_key_exists($city, $this->citys)){
            $this->unit = $this->citys[$city];
            $this->city = $city;
            return true;
        }
        return false;
    }
    
    //打折
    private function getPercentOff() {
        if ($this->listPrice != $this->sellingPrice && $this->listPrice != 0) {
            $this->percentOff = intval($this->sellingPrice / $this->listPrice * 10);
            $this->trace('打折: ' . (string) $this->percentOff);
        }
    }
    
    //下载图片
    protected function downImage($links) {
        $bRet = false;

        if (!isset($links{0}))
            return $bRet;
        if (!$this->id)
            return $bRet;

        $savename = dirname(Yii::app()->basePath) . '/product/' . $this->id;
        if (!is_dir($savename)) {
            if (!mkdir($savename, 0777))
                throw new CException('创建目录失败');
            @chmod($savename, 0777);
        }

        Yii::import('lib.functions');
        $savename .= '/' . getImageName($links);
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
                    resizeJpg($savename, 336, 596);
            }else {
                //Yii::log('img_data error');
            }
        } else {
            $bRet = true;
        }

        return $bRet;
    }
    
    //保存信息
    protected function save() {
        if (!isset($this->pid))
            return false;
        
        $id = $this->id;

        $product = Product::model()->findByPk($id);
        if ($product === null && $this->isInStock===false) {//新货，但无货存
            $this->trace('新建商品无库不进行入库！');
            return false;
        } elseif ($product === null) {
            $product = new Product;
            $product->id = $id;
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            if ($this->stock != 0) {
                $this->getPercentOff();

                if (isset($this->pid))
                    $product->pid = $this->pid;

                if (isset($this->brandName)){
                    $product->brandName = CHtml::decode($this->brandName);
                }

                if (isset($this->productTitle)){
                    $product->productTitle = CHtml::decode($this->productTitle);
                }

                if (isset($this->listPrice))
                    $product->originalPrice = trim($this->listPrice);

                if (isset($this->sellingPrice))
                    $product->price = trim($this->sellingPrice);

                if (isset($this->percentOff))
                    $product->percentOff = $this->percentOff;

                if (isset($this->desc))
                    $product->desc = $this->desc;

                if (isset($this->details))
                    $product->details = $this->details;

                if (isset($this->designer))
                    $product->designer = $this->designer;

                if (isset($this->sizeFitContainer))
                    $product->sizeFitContainer = $this->sizeFitContainer;

                if (isset($this->tags)){
                    $product->saveTags($this->tags);
                }

                if (isset($this->unit))
                    $product->unit = $this->unit;
                
                if (isset($this->area)){
                    $product->area = $this->area;
                }

                if (isset($this->url))
                    $product->url = $this->url;

                if (isset($this->sizes)) {
                    $product->saveSizeAttri($this->sizes);
                }

                if (isset($this->alternative)) {
                    $product->alternative = $this->alternative;
                }

                if (isset($this->colors))
                    $product->saveColorAttri($this->colors);

                if (isset($this->sku))
                    $product->saveSku($this->sku);

                if (isset($this->images))
                    $product->saveProductImages($this->images);

                $product->station = $this->station;

                $product->city = $this->city;
            }

            if (isset($this->stock))
                $product->stock = $this->stock;

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
}

?>
