<?php
Yii::import('lib.Functions');
Yii::import('lib.taobao.TbInterface');

/**
 * Description of Taobao
 *2014/12/1
 * @author stephenlee
 */
class Taobao {

    public static function getAuthorizeUrl() {
        return Yii::app()->params['top_url'] . '?appkey=' . Yii::app()->params['AppKey'] . '&encode=utf-8';
    }

    //得到用户交易数据
    public static function getTradesSold($userid, $stime, $etime) {
        $topsession = User::findUserSession($userid);
        if ($topsession === false)
            return false;

        return TbInterface::getTradesSold($stime, $etime, $topsession);
    }

    public static function itemUpdateDelisting($num_iid) {
        $topsession = User::findUserSession();
        if ($topsession === false)
            return false;

        $reps = TbInterface::itemUpdateDelistingRequest($num_iid, $topsession);
        if (isset($reps->item))
            return true;

        return false;
    }

    //删除单条商品
    public static function deleteItem($num_iid) {
        $topsession = User::findUserSession();
        if ($topsession === false)
            return false;

        $reps = TbInterface::taobaoItemDelete($num_iid, $topsession);
        if (isset($reps->item))
            return true;

        return false;
    }

    //解析淘宝友好名
    public static function decodeNickName($top_parameters) {
        $decstr = base64_decode($top_parameters);
        parse_str($decstr, $params);

        $nickname = $params['visitor_nick'];

        if (strlen($nickname) <= 0)
            return false;

        return $nickname;
    }

    //为已授权的用户开通消息服务 
    public static function tmcUserPermit($topsession) {
        return TbInterface::tmcUserPermit($topsession);
    }

    //解析失效时间
    public static function decodeExpiresIn($top_parameters) {
        $decstr = base64_decode($top_parameters);
        parse_str($decstr, $params);

        return $params['expires_in'];
    }

    public static function getSellercatsList() {
        $nick = User::getUserNick(Yii::app()->user->id);
        if($nick === false){
            throw new CHttpException(2010, '用户淘宝昵称为空，无法上传，请先进行淘宝授权!');
            return;
        }
        
        Yii::import('lib.TaobaoCache');
        $value = TaobaoCache::getValue($nick);
        if ($value === false) {
            $reps = TbInterface::getSellercats($nick);
            if (isset($reps->seller_cats)) {
                $value = $reps->seller_cats;

                TaobaoCache::setValue($nick, $value);
            } else {
                //throw new CHttpException($reps->code, $reps->sub_msg);
                return false;
            }
        }
        return $value;
    }

    //延长授权期
    public static function refreshToken($session, $top_parameters) {
        $decstr = base64_decode($top_parameters);
        parse_str($decstr, $params);

        return Yii::app()->params['top_url'] . '/refresh?appkey=' .
                Yii::app()->params['AppKey'] . '&refresh_token=' .
                $params['refresh_token'] . '&sessionkey=' .
                $session . '&sign=' . Taobao::generateSign(Yii::app()->params['AppKey'], $top_parameters, $session, Yii::app()->params['AppSecret']);
    }

    //生成签名
    private static function generateSign($top_appkey, $top_parameters, $top_session, $app_secret) {
        return base64_encode(md5($top_appkey . $top_parameters . $top_session . $app_secret));
    }

    //上传商品
    public static function uploadProduct($id) {
        //Yii::app()->user->refreshToken();
        $uploadInfo = new uploadInfo;
        if ($uploadInfo->find($id) === false) {
            Functions::message('找不到上传商品信息，请重试!');
            unset($uploadInfo);
            return false;
        }

        $num_iid = TbInterface::getCustomItemInfo($id);
        if ($num_iid !== false)
            return Taobao::updateProduct($uploadInfo, $num_iid);

        return Taobao::addProduct($uploadInfo);
    }

    //添加新商品
    private static function addProduct($uploadInfo) {

        Functions::message('正在上传商品。。。');

        $info = array();
        $uploadInfo->countSku();
        if ($uploadInfo->num <= 0) {
            Functions::message('货存为零，不进行上传!');
            return false;
        }

        //上传商品
        $images = Taobao::uploadproductImgs($uploadInfo->id);
        if (count($images) <= 0) {
            Functions::message('图片上传失败，不进行上传!');
            return false;
        }


        $info['outer_id'] = $uploadInfo->id;
        Functions::message('outer_id：' . $info['outer_id']);

        $info['num'] = $uploadInfo->num;
        Functions::message('num：' . $info['num']);

        $info['price'] = $uploadInfo->getTotalPrice();
        Functions::message('price：' . $info['price']);

        $info['cid'] = $uploadInfo->itemCats;
        Functions::message('cid：' . $info['cid']);

        $info['props'] = $uploadInfo->props;
        Functions::message('props：' . $info['props']);

        $info['input_pids'] = $uploadInfo->inputPids;
        Functions::message('input_pids：' . $info['input_pids']);

        $info['input_str'] = $uploadInfo->inputStr;
        Functions::message('input_str：' . $info['input_str']);

        $info['title'] = $uploadInfo->getProdutTitle();
        Functions::message('title：' . $info['title']);

        $info['state'] = City::getStatePkByCity($uploadInfo->city);
        Functions::message('state：' . $info['state']);

        $info['city'] = $uploadInfo->city;
        Functions::message('city：' . $info['city']);

        $info['sellercats'] = $uploadInfo->sellercats;
        Functions::message('sellercats：' . $info['sellercats']);

        $info['propertyAlias'] = $uploadInfo->propertyAlias;
        Functions::message('propertyAlias：' . $info['propertyAlias']);

        $info['image'] = Taobao::getFirstImage($uploadInfo->id);
        $info['desc'] = $uploadInfo->productDesc($images);

        if (isset($uploadInfo->sku_quantities{0})) {
            $info['sku_properties'] = $uploadInfo->sku_properties;
            Functions::message('sku_properties：' . $info['sku_properties']);
            $info['sku_quantities'] = $uploadInfo->sku_quantities;
            Functions::message('sku_quantities：' . $info['sku_quantities']);
            $info['sku_prices'] = $uploadInfo->sku_prices;
            Functions::message('sku_prices：' . $info['sku_prices']);
            $info['sku_ids'] = $uploadInfo->sku_ids;
            Functions::message('sku_ids：' . $info['sku_ids']);
        }

        $resp = TbInterface::itemAddRequest($info);
        unset($info);
        if (!isset($resp->item->num_iid)) {
            unset($uploadInfo);
            Functions::message('上传错误,请将如下代码发送给管理员！');
            Functions::message($resp);
            echo '<br/>';
            return false;
        }
        //得到上传淘宝商品ID
        $num_iid = $resp->item->num_iid;
        $detail_url = TbInterface::getItemAddr($num_iid);
        
        $codes = $uploadInfo->getColorsCode();

        //管理颜色属性
        self::itemJointPropimg($num_iid, $codes, $images);
        
        Uploadhistory::saveUploadHistory($uploadInfo->id, $num_iid, $detail_url);
        
        unset($uploadInfo);
        echo '<br/>';
        echo CHtml::link('查看店铺商品链接', $detail_url);
        
        return $num_iid;
    }

    public static function itemJointPropimg($num_iid, $codes, $images) {
        
        foreach ($codes as $key => $code) {
            if(isset($images[$code][0]))
                TbInterface::ItemJointPropimgRequest($num_iid, $key, $images[$code][0]);
        }
    }

    //得到标准商品属性
    public static function getItemProps($cid, $child_path = '') {
        Yii::import('lib.TaobaoCache');
        $value = TaobaoCache::getValue($cid . $child_path);
        if ($value === false) {
            $reps = TbInterface::getItemProps($cid, $child_path);
            if (isset($reps->item_props)) {
                $value = $reps;
                TaobaoCache::setValue($cid . $child_path, $value);
            }
        }
        return $value;
    }

    //得到标准商品类目
    public static function getItemcats($pid, $cids = 0) {
        Yii::import('lib.TaobaoCache');
        $value = TaobaoCache::getValue($pid);
        if ($value === false) {
            $reps = TbInterface::getItemcats($pid, $cids);
            if (isset($reps->item_cats)) {
                $value = $reps;
                TaobaoCache::setValue($pid, $value);
            }
        }
        return $value;
    }

    public static function getFirstImage($pid) {
        $image = ProductImages::getFirstImage($pid);
        return '@' . getcwd() . '/product/' . $pid . '/' . $image;
    }

    //上传描产品图片
    public static function uploadproductImgs($pid) {
        $arryImgs = ProductImages::getProductImages($pid);

        $images = array();
        
        Functions::message('查找到图片数='.  count($arryImgs));
        
        foreach ($arryImgs as $img) {
            $imgfile = '@' . getcwd() . '/product/' . $pid . '/' . $img['image'];
            $imgPath = getcwd() . '/product/' . $pid . '/' . $img['image'];

            if (file_exists($imgPath)) {
                if (TbInterface::imgAffix($imgfile)) {
                    if (($path = TbInterface::pictureUpload($imgfile)) !== false){
                        $images[$img['code']][] = $path;
                    }else{
                        Functions::message('上传图片失败:'.  $imgfile);
                    }
                }
            }else {
                Functions::message('不存在目录:'.  $imgPath);
            }
        }

        return $images;
    }

    //更新新商品
    public static function updateProduct($uploadInfo, $num_iid) {
        
        
        Functions::message('正在更新商品。。。');

        $info = array();
        $uploadInfo->countSku();
        if ($uploadInfo->num <= 0) {
            unset($uploadInfo);
            unset($info);
            Functions::message('货存为零，对商品进行下架!');
            $reps = TbInterface::itemUpdateDelistingRequest($num_iid, Yii::app()->user->topsession);
            if(isset($reps->item)) Functions::message('商品下架成功!');
            return $num_iid;
        }

        $info['num_iid'] = $num_iid;
        Functions::message('num_iid：' . $info['num_iid']);
        
        $info['num'] = $uploadInfo->num;
        Functions::message('num：' . $info['num']);

        $info['price'] = $uploadInfo->getTotalPrice();
        Functions::message('price：' . $info['price']);

        $info['cid'] = $uploadInfo->itemCats;
        Functions::message('cid：' . $info['cid']);

        $info['props'] = $uploadInfo->props;
        Functions::message('props：' . $info['props']);

        $info['propertyAlias'] = $uploadInfo->propertyAlias;
        Functions::message('propertyAlias：' . $info['propertyAlias']);

        $info['input_pids'] = $uploadInfo->inputPids;
        Functions::message('input_pids：' . $info['input_pids']);

        $info['input_str'] = $uploadInfo->inputStr;
        Functions::message('input_str：' . $info['input_str']);

        $info['title'] = $uploadInfo->getProdutTitle();
        Functions::message('title：' . $info['title']);

        $info['state'] = City::getStatePkByCity($uploadInfo->city);
        Functions::message('state：' . $info['state']);

        $info['city'] = $uploadInfo->city;
        Functions::message('city：' . $info['city']);

        //$info['sellercats'] = $uploadInfo->sellercats;
        //Functions::message('sellercats：' . $info['sellercats']);

        if (isset($uploadInfo->sku_quantities{0})) {
            $info['sku_properties'] = $uploadInfo->sku_properties;
            Functions::message('sku_properties：' . $info['sku_properties']);
            $info['sku_quantities'] = $uploadInfo->sku_quantities;
            Functions::message('sku_quantities：' . $info['sku_quantities']);
            $info['sku_prices'] = $uploadInfo->sku_prices;
            Functions::message('sku_prices：' . $info['sku_prices']);
            $info['sku_ids'] = $uploadInfo->sku_ids;
            Functions::message('sku_ids：' . $info['sku_ids']);
        }

        $resp = TbInterface::itemUpdateRequest($info);
        
        unset($info);
        if (!isset($resp->item)) {
            unset($uploadInfo);
            Functions::message('更新错误,请将如下代码发送给管理员！');
            Functions::message($resp);
            echo '<br/>';
            return false;
        }
        
        Functions::message('更新商品成功');
        
        $detail_url = Uploadhistory::updateUploadHistory($uploadInfo->id, $num_iid);
        unset($uploadInfo);
        if($detail_url !== false)
            echo CHtml::link('查看店铺商品链接', $detail_url);
        echo '<br/>';
        //得到上传淘宝商品ID
        return $resp->item->num_iid;
    }

    public static function getItemAddr($num_iid) {
        return TbInterface::getItemAddr($num_iid);
    }
}

?>
