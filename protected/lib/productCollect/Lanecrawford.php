<?php
include_once('CCollect.php');
/* * *******************************************************************************
 * Copyright(C),2013, Glory
 * FileName: Lanecrawford.php
 * Author:  stephen
 * Version: v1.0
 * Date:  14:07 2013-06-12
 * Description:  Lanecrawford采集类
 * ******************************************************************************** */

class Lanecrawford extends CCollect {

    const STATION = 'LANECRAWFORD';
    const SIZECHART_URL = 'http://www.lanecrawford.com.cn/product/include/productSizeGuide.jsp?productId=';
    const HOME_URL = 'http://www.lanecrawford.com.cn/?_country=CN';
    const HK_HOST = 'http://www.lanecrawford.com';
    const CN_HOST = 'http://www.lanecrawford.com.cn';

    private $hk_url;
    private $cn_url;

    //构造函数
    function __construct($trace = false, $bFindAlternativeProduct = false) {
        parent::__construct($trace, $bFindAlternativeProduct);
    }

    //销毁函数
    function __destruct() {
        parent::__destruct();
    }

    public function init() {
        $this->info['unit'] = 'HKD';
        $this->station = self::STATION;
        $this->citys[] = '香港';
    }

    //解析URL
    private function parseUrl($url) {
        $array_query = parse_url($url);

        $this->hk_url = self::HK_HOST . $array_query['path'];
        $this->cn_url = self::CN_HOST . $array_query['path'];
    }

    //得到香港站信息
    private function getHKHtmlInfo() {
        $this->getpid($this->dom);
        $nodes = $this->dom->find('.lc-product-details');
        foreach ($nodes as $html) {

            if ($this->getStock($html) > 0) {
                $this->getPrice($html);
            } else {
                return false;
            }
            break;
        }
        return true;
    }

    //解析单个商品
    public function productAnaly($url, $city = '') {

        parent::productAnaly($url, $city);

        //分解URL
        $this->parseUrl($url);

        //采集香港站
        $htmlhk = null;
        if (isset($this->hk_url{0})) {
            $this->trace('开始抓取商品香港站信息:' . $this->hk_url);
            if (!$this->getInterHtmlDom($this->hk_url)) {
                $this->trace('香港URL抓取失败');
                return false;
            }

            if (!$this->getHKHtmlInfo($htmlhk)) {
                return $this->saveInfo();
            }
        }

        if (isset($this->cn_url{0})) {
            $this->trace('开始抓取商品中国URL:' . $this->cn_url);
            $dom = $this->getHtmlDom($this->cn_url);
            if ($dom !== false && $this->isNoStock() > 0) {
                $this->trace('采集中国站信息。');
                $this->setDom($dom);
            }else{
                $this->trace('中国抓取失败, 采集香港站信息。');
            }
        }
    
        $this->setProductInfo();
        
        return $this->saveInfo();
    }

    public function collectPage($url, $city = '') {
        $this->trace('此网点不需要翻页处理.');
    }

    //采集单页面
    public function collectSinglePage($url, $city = '') {
        $this->trace("采集整页: {$url}");

        $ret = false;
        try {
            $html = $this->getHtmlDom($url);
            if ($html !== false) {
                $links = $this->findProductLinks($html);
                $this->trace("查找到商品数: " . strval(count($links)));
                $html->clear();
                unset($html);
            }
        } catch (Exception $e) {  
        }

        $this->saveProductLinks($links);
        unset($links);
        
        return true;
    }

    //得到页数 <span class="page-numbers">第3/10页</span>
    public function getPageCount($url) {
        $pageCount = 0;

        $reps = $this->curl_cookie_url($url);
        if ($reps == false)
            return $pageCount;

        $html = str_get_html($reps);
        if ($html == null)
            return $pageCount;

        $ret = $html->find('span.page-numbers');
        if ($ret == null)
            return $pageCount;
        foreach ($ret as $key => $info) {
            $as = strpos($info, '/');
            $ae = strpos($info, '页');

            $pageCount = intval(substr($info, $as + 1, $ae - $as));

            $this->trace('搜索到页数: ' . (string) $pageCount);

            return $pageCount;
        }

        return $pageCount;
    }

    //得到当前页码
    private function getcurrentPageNum($url) {
        $array_query = parse_url($url);

        $page = 1;
        try {
            if (isset($array_query['query'])) {
                $item = explode('=', $array_query['query']);
                $page = intval($item[1]);
            }
        } catch (Exception $e) {
            
        }

        return $page;
    }

    //得到商品信息
    public function getProductInfo($desc) {
        if (array_key_exists($desc, $this->info))
            return $this->info[$desc];
        return false;
    }

    //搜索商品链接
    private function findProductLinks($html) {

        $temps = array();
        $links = array();

        foreach ($html->find('div[class=rol] a') as $key => $element) {
            if ($key == self::MAX_P)
                break;
            $temps[] = self::CN_HOST . $element->href;
            $this->trace('搜索到商品链接:' . self::CN_HOST . $element->href);
        }


        $links = array_unique($temps);
        unset($temps);

        return $links;
    }

    //设置商品信息
    private function setProductInfo() {
        $nodes = $this->dom->find('#productDetails');
        foreach ($nodes as $html) {
            $this->getBrand($html);
            $this->getTitle($html);
            $this->getImages($html);
            $this->getProductIntroduce($html);
        }
    }

    //得到品牌
    private function getBrand($html) {
        $element = $html->find('.lc-product-brand', 0);
        $this->info['brandName'] = trim($element->plaintext);

        $this->trace('品牌:' . $this->info['brandName']);
    }

    //商品编号
    private function getpid($html) {
        $sid = $html->find('code[restinject=productCode]', 0);
        $this->info['pid'] = trim($sid->plaintext);
        $this->info['id'] = self::STATION . $this->info['pid'];

        $this->trace("编号:" . $this->info['id']);
    }

    //标题
    private function getTitle($html) {
        $element = $html->find('.lc-product-short-description', 0);

        $this->info['productTitle'] = trim($element->plaintext);
        $this->trace('商品名:' . $this->info['productTitle']);
    }

    private function isNoStock() {
    
        if ($this->info['stock'] > 0) {
            return true;
        }
        return false;
    }

    //打折
    private function getPercentOff() {
        
    }

    //价格
    private function getPrice($html) {

        $element = $html->getElementById('#product-price');
        $this->info['price'] = self::analyPrice(trim ($element->innertext));
        
        $orielement = $html->getElementById('#price-original');
        if(!empty($orielement))
            $this->info['originalRetailPrice'] = self::analyPrice(trim ($element->innertext));
        
        
        $this->trace('现价:' . $this->info['price']);
    }

    //图片
    private function getImages($html) {
        $this->info['images'] = array();
        $this->info['images']['0'] = array();
        foreach ($html->find('.lc-product-thumb') as $element) {
            $imgAddr = trim($element->getAttribute('href'));
            if($this->downImage($imgAddr))
                $this->info['images']['0'][] = $imgAddr;
        }

        $this->trace('图片:');
        $this->trace($this->info['images']);
    }

    //计算库存
    private function getStock($html) {
        $this->info['stock'] = 0;
        
        $sizes = array();       
        $this->info['colors'] = array();
        $colorobj = new stdclass;
        $colorobj->code = '0';
        $colorobj->image = null;
        $colorobj->name = '图片色';
        $this->info['colors'][] = $colorobj;

        $this->info['sku'] = array();
        $this->info['sizes'] = array();

        foreach ($html->find('.lc-size-swatch') as $element) {
            $bvalue = stripos($element->class, 'lc-size-outofstock');
            $sku = new stdClass;
                $sku->size = trim($element->innertext);
                $sku->color = '图片色';
                $sizeobj = new stdClass;
                $sizeobj->name = $sku->size;
                $sizeobj->code = '0';
                $sizeobj->image = null;
                $this->info['sizes'][] = $sizeobj;
                $sizes[] = $sku->size;
            if ($bvalue===false) {
                $sku->count = 5;
            }else{
                $sku->count = 0;
            }
            $this->info['stock'] += $sku->count;
            $this->info['sku'][] = $sku;
        }

        $this->trace('尺寸:');
        $this->trace($this->info['sizes']);

        return $this->info['stock'];
    }

    //说明
    private function getProductIntroduce($html) {
            $el = $html->getElementById('#lc-product-details-care');
            if(!empty($el)){
                $this->info['desc'] = trim($el->innertext);
                $this->trace('详细:' . $this->info['desc']);
            }
            
            $el = $html->getElementById('#lc-product-fit-styling');
            if(!empty($el)){
                $this->info['sizeFitContainer'] = trim($el->innertext);
                $this->trace('尺寸描述:' . $this->info['sizeFitContainer']);
            }
            
            $el = $html->getElementById('#lc-product-shipping-return');
            if(!empty($el)){
                $this->info['details'] = trim($el->innertext);
                $this->trace('商品信息:' . $this->info['details']);
            } 
        
        }

}