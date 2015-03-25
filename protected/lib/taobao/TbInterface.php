<?php
include('TopSdk.php');
Yii::import('lib.Functions');
/* * *******************************************************************************
 * Copyright(C),2013, Glory
 * FileName: Shopbop.php
 * Author:  stephen
 * Version: v1.0
 * Date:  2015/03/25 9:34
 * Description:  淘宝函数分装类
 * ******************************************************************************** */

class TbInterface {

    //下架商品
    public static function itemUpdateDelistingRequest($numIid,$sessionKey) {

        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new ItemUpdateDelistingRequest;
        $req->setNumIid($numIid);
        $resp = $tbClient->execute($req, $sessionKey);

        return $resp;
    }

    //删除单条商品
    public static function taobaoItemDelete($num_iid, $sessionKey) {
        $c = new TopClient;
        $c->appkey = Yii::app()->params['AppKey'];
        $c->secretKey = Yii::app()->params['AppSecret'];
        $req = new ItemDeleteRequest;
        $req->setNumIid($num_iid);
        return $c->execute($req, $sessionKey);
    }

    //为已授权的用户开通消息服务 
    public static function tmcUserPermit($topsession) {
        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];
        $req = new TmcUserPermitRequest;
        $req->setTopics("taobao_trade_TradeCreate ,taobao_trade_TradeClose,taobao_trade_TradeCloseAndModifyDetailOrder,taobao_item_ItemDelete,taobao_trade_TradeSuccess");
        $resp = $tbClient->execute($req, $topsession);
        if (isset($resp->is_success)) {
            Functions::message($resp);
            return true;
        }
        return false;
    }

    //得到用户交易数据
    public static function getTradesSold($stime, $etime, $sessionKey) {
        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new TradesSoldGetRequest;
        $req->setFields("post_fee,buyer_nick,tid,receiver_name, receiver_state, receiver_city, receiver_district,receiver_address, orders.oid,orders.num_iid,orders.payment,buyer_nick,orders.pic_path,orders.num,orders.title,orders.end_time,orders.divide_order_fee,created,");
        $req->setStartCreated($stime);
        $req->setEndCreated($etime);
        $req->setPageNo(1);
        $req->setPageSize(40);
        $req->setUseHasNext("true");
        $req->setStatus("TRADE_FINISHED");
        $resp = $tbClient->execute($req, $sessionKey);
        unset($tbClient);
        unset($req);

        if (isset($resp->trades))
            return $resp;
        return false;
    }

    //得到标准商品类目
    public static function getItemcats($pid, $cids = 0) {

        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new ItemcatsGetRequest;
        $req->setFields("cid,parent_cid,name,is_parent");
        $req->setParentCid($pid);

        if ($cids != 0)
            $req->setCids($cids);

        $resp = $tbClient->execute($req);
        unset($tbClient);
        unset($req);

        return $resp;
    }

    //得到标准商品属性
    public static function getItemProps($cid, $child_path = '') {
        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new ItempropsGetRequest;
        $req->setFields("parent_pid,is_allow_alias,pid,name,must,multi,prop_values,is_color_prop,is_enum_prop,is_input_prop,is_item_prop,is_key_prop,is_sale_prop,child_template");
        $req->setCid($cid);

        if (strlen($child_path) > 0)
            $req->setChildPath($child_path);

        $resp = $tbClient->execute($req);

        unset($tbClient);
        unset($req);
        
        //var_dump($resp);

        return $resp;
    }

    //得到卖家商品类目
    public static function getSellercats($nick) {
        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new SellercatsListGetRequest;
        $req->setNick($nick);
        $resp = $tbClient->execute($req);

        unset($tbClient);
        unset($req);

        return $resp;
    }

    //关联属性图片
    public static function ItemJointPropimgRequest($num_iid, $colorcode, $imges) {

        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new ItemJointPropimgRequest;
        $req->setProperties($colorcode);
        $req->setPicPath(TbInterface::getPicPath($imges));
        $req->setNumIid((string) $num_iid);
        $resp = $tbClient->execute($req, Yii::app()->user->topsession);
        unset($req);
        unset($path);
    }

    //上传属性图片
    public static function itemPropimgUpload($num_iid, $uploadInfo, $productImgs) {
        $keys = $uploadInfo->getSkuImgKeys();
        if (is_array($keys)) {
            $colorids = explode(",", $uploadInfo->colorProperties);
            foreach ($keys as $key) {
                $this->ItemJointPropimgRequest($colorids[$key], $productImgs[$key]);
            }
        } else {
            ItemJointPropimgRequest($num_iid, $uploadInfo->colorProperties, $this->productImgs[0]);
        }

        Functions::message('关联属性图片');
    }

    //上传图片
    public static function pictureUpload($fileLocation) {

        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new PictureUploadRequest;
        $req->setPictureCategoryId(0);
        $req->setImg($fileLocation);

        $req->setImageInputTitle(TbInterface::getImageName($fileLocation));

        $resp = $tbClient->execute($req, Yii::app()->user->topsession);
        unset($req);
        unset($tbClient);

        if (isset($resp->picture) && isset($resp->picture->picture_path)) {
            return $resp->picture->picture_path;
        } else {
            Functions::message($resp);
        }

        return false;
    }

    public static function getImageName($imgAddr) {
        $path_parts = pathinfo($imgAddr);
        return $path_parts["basename"];
    }

    //更新商品
    public static function itemUpdateRequest($info) {
        
        if (!isset($info['num_iid'])) return false;

        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];
        
        $req = new ItemUpdateRequest;

        $req->setNumIid($info['num_iid']);
        $req->setNum($info['num']);
        $req->setPrice($info['price']);
        $req->setTitle($info['title']);
        $req->setCid($info['cid']);
        $req->setLocationState($info['state']);
        $req->setLocationCity($info['city']);

        if (isset($info['input_pids']{0}) && isset($info['input_str']{0})) {
            $req->setInputPids($info['input_pids']);
            $req->setInputStr($info['input_str']);
        }

        if (isset($info['props']))
            $req->setProps($info['props']);

        if (isset($info['sku_properties']{0})) {
            $req->setSkuProperties($info['sku_properties']);
            $req->setSkuQuantities($info['sku_quantities']);
            $req->setSkuPrices($info['sku_prices']);
            $req->setSkuOuterIds($info['sku_ids']);
        }
        
        if (isset($info['sellercats']{0}))
            $req->setSellerCids(','.$info['sellercats']);

        if (isset($info['propertyAlias']{0}))
            $req->setPropertyAlias($info['propertyAlias']);

        $resp = $tbClient->execute($req, Yii::app()->user->topsession);
        
        unset($req);
        unset($tbClient);

        return $resp;
    }

    //通过商品外包ID得到商品信息
    public static function getCustomItemInfo($id) {
        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new ItemsCustomGetRequest;
        $req->setOuterId($id);
        $req->setFields("num_iid");
        $resp = $tbClient->execute($req, Yii::app()->user->topsession);

        unset($tbClient);
        unset($req);

        if (!isset($resp->items) || !isset($resp->items->item[0]))
            return false;

        return $resp->items->item[0]->num_iid;
    }

    //添加商品
    public static function itemAddRequest($info) {
        if (!is_array($info))
            return false;

        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];

        $req = new ItemAddRequest;
        $req->setOuterId($info['outer_id']);
        $req->setPrice($info['price']);
        $req->setNum($info['num']);
        $req->setType("fixed");
        $req->setStuffStatus("new");
        $req->setTitle($info['title']);
        $req->setDesc($info['desc']);
        $req->setLocationState($info['state']);
        $req->setLocationCity($info['city']);
        $req->setProps($info['props']);
        $req->setCid($info['cid']);
       
        //卖点
        $req->setSellPoint($info['title']);
        //发票
        $req->setHasInvoice('false');
        //保修
        $req->setHasWarranty('false');
         //无退货
        $req->setSellPromise('false');
        //7天退货
        $req->setNewprepay("0");
        
        $req->setGlobalStockType("2");
        $req->setGlobalStockCountry($info['city']);
        
        if(isset($info['sell_point ']{0})){
            $reg->setSellPoint($info['sell_point ']);
        }

        if (isset($info['image']))
            $req->setImage($info['image']);

        if (isset($info['propertyAlias']{0}))
            $req->setPropertyAlias($info['propertyAlias']);

        if (isset($info['sellercats']{0}))
            $req->setSellerCids(','.$info['sellercats']);

        if (isset($info['input_pids']{0}) && isset($info['input_str']{0})) {
            $req->setInputPids($info['input_pids']);
            $req->setInputStr($info['input_str']);
        }

        if (isset($info['sku_properties']{0})) {
            $req->setSkuProperties($info['sku_properties']);
            $req->setSkuQuantities($info['sku_quantities']);
            $req->setSkuPrices($info['sku_prices']);
            $req->setSkuOuterIds($info['sku_ids']);
        }

        $resp = $tbClient->execute($req, Yii::app()->user->topsession);
        unset($tbClient);
        unset($req);

        return $resp;
    }

    //上传图片附件，返回附件名
    public static function imgAffix($imgFile) {
        $paramArr = array();

        $paramArr['image'] = $imgFile;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
        curl_setopt($ch, CURLOPT_URL, Yii::app()->params['gatewayUrl']);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result = curl_exec($ch);

        curl_close($ch);

        unset($paramArr);
        if ($result === false) {
            Functions::message('imgFile false,path:' . $imgFile);
            return false;
        }

        return true;
    }

    //上传描产品图片
    public static function uploadproductImgs($uploadInfo) {
        $arryImgs = ProductImages::getProductImages($this->attributes['id']);

        Yii::import('lib.Functions');
        foreach ($arryImgs as $img) {
            $imgfile = '@' . getcwd() . '/product/' . $uploadInfo->id . '/' . $img['image'];
            $imgPath = getcwd() . '/product/' . $uploadInfo->id . '/' . $img['image'];
            if (file_exists($imgPath)) {
                $affix = Functions::imgAffix($imgFile);
                if ($affix !== false) {
                    $uploadInfo->attributes[$img['code']][] = $this->pictureUpload($imgfile);
                }
            }
        }
    }

    //得到商品地址
    public static function getItemAddr($num_iid) {
        $tbClient = new TopClient;
        $tbClient->appkey = Yii::app()->params['AppKey'];
        $tbClient->secretKey = Yii::app()->params['AppSecret'];
        $req = new ItemGetRequest;
        $req->setFields("num_iid,detail_url");
        $req->setNumIid($num_iid);

        $sessionKey = Yii::app()->user->getCurrUserSession();
        $resp = $tbClient->execute($req, $sessionKey);

        unset($req);
        unset($tbClien);

        if (isset($resp->item) && isset($resp->item->detail_url))
            return $resp->item->detail_url;

        return false;
    }

    

    //添加图片到淘宝
    public static function jointProductImage() {
        if (isset($this->productImgs[0])) {
            $req = new ItemJointImgRequest;
            //$req->setPicPath("i4/3594260278/T2diSyXbXaXXXXXXXX_!!3594260278.gif");
            $req->setPicPath($this->getPicPath($this->productImgs[0]));
            $req->setNumIid((string) $this->num_iid);
            $req->setIsMajor("true");

            $resp = $this->tbClient->execute($req, $this->sessionKey);
        }
        $this->message("关联商品图片.");
    }

    public static function getPicPath($PicPath) {
        $find = "imgextra";
        $count = strrpos($PicPath, $find);
        return substr_replace($PicPath, "", 0, $count + strlen($find) + 1);
    }

}

?>