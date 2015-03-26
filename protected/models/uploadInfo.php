<?php
/**
 * Description of uploadInfo
 *2014/12/1
 * @author Administrator
 */
class uploadInfo {

    public $attributes;

    function find($id) {

        $row = Yii::app()->db->createCommand()
                ->select('p.*, a.*, a.freight as pfreight')
                ->from('tbl_product p')
                ->join('tbl_product_property a', 'a.productId=p.id AND a.userID=:userID', array(':userID' => Yii::app()->user->id))
                ->where('p.id=:pid', array(':pid' => $id))
                ->queryRow();

        if ($row === false) {
            Yii::log('查询失败!');
            return false;
        }

        $this->attributes = $row;
        /*

          if(isset($this->attributes['price'])){
          $this->attributes['price'] = Sku::getMaxPrice($this->attributes['id']);
          } */
        return true;
    }

    public function getProdutTitle() {

        /*
          if (defined('YII_DEBUG') && YII_DEBUG === true) $this->attributes['subTitle'] = '沙箱测试' . $this->attributes['subTitle'];

          $len = (strlen($this->attributes['subTitle']) + mb_strlen($this->attributes['subTitle'], 'UTF8')) / 2;
          if ($len > 60) {
          Yii::import('lib.Functions');
          $this->attributes['subTitle'] = mb_strcut($this->attributes['subTitle'], 0, 60, 'UTF8');
          Functions::message('标题过长剪辑标题');
          } */

        return CHtml::decode($this->attributes['subTitle']);
    }

    //销毁函数
    function __destruct() {
        unset($this->attributes);
    }

    //计算SKU
    public function countSku() {
        $this->attributes['num'] = $this->attributes['stock'];
        
        Yii::log('product stock=' . $this->attributes['num']);
        
        if (!isset($this->attributes['colorProperties']{0}) ||
                !isset($this->attributes['sizeProperties']{0})) {
            Yii::log('countSku: colorProperties, sizeProperties未设置!');
            return false;
        }

        $colors = explode(",", $this->attributes['colorProperties']);
        $sizes = explode(",", $this->attributes['sizeProperties']);

        $sku_prices = array();
        $sku_quantities = array();
        $sku_properties = array();
        foreach ($colors as $color) {
            $colorvalue = explode(";", $color);
            foreach ($sizes as $size) {
                $sizevalue = explode(";", $size);
                $sku_properties[] = $colorvalue[0] . ';' . $sizevalue[0];
                $skuinfo = Sku::findFromPid($this->attributes['id'], $colorvalue[1], $sizevalue[1]);
                if ($skuinfo === false) {
                    $sku_quantities[] = 0;
                    $sku_prices[] = $this->getTotalPrice();
                } else {
                    $sku_quantities[] = $skuinfo['count'];
                    $sku_prices[] = $this->getPrice($skuinfo['price']);
                }
            }
        }
        $this->attributes['sku_properties'] = implode(',', $sku_properties);
        $this->attributes['sku_quantities'] = implode(',', $sku_quantities);
        $this->attributes['sku_prices'] = implode(',', $sku_prices);
        if (isset($this->attributes['sku_quantities']{0})) {
            $this->setSkuOuterIds($this->attributes['sku_quantities']);
            $this->attributes['num'] = array_sum($sku_quantities);
        }
        
        
        return true;
    }

    //得到SkuOuterIds
    private function setSkuOuterIds($skuProperties) {
        $ret = array();
        $id = '';
        $properties = explode(',', $skuProperties);
        $ret = array_pad($ret, count($properties), $id);

        $this->attributes['sku_ids'] = implode(',', $ret);
    }

    public function __isset($name) {
        if (isset($this->attributes[$name])) {
            return true;
        }

        return false;
    }

    public function __get($name) {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return null;
    }

    public function getPrice($price) {
        $cost = Express::getCost($this->attributes['station'], $this->attributes['unit'], $this->attributes['price']);
        $freight = $this->attributes['pfreight'];
        $unitprice = ExchangeRate::countPriceRate($price, $this->attributes['percentOff'], $this->attributes['unit'], $this->attributes['station']);
        return ceil((float) $cost + (float) $freight + (float) $unitprice);
    }

    public function getTotalPrice() {
        if (!isset($this->attributes['totalPrice']))
            $this->attributes['totalPrice'] = $this->getPrice($this->attributes['price']);
        return $this->attributes['totalPrice'];
    }

    public function getImages() {
        return ProductImages::getImages($this->attributes['id']);
    }

    public function setAddInfo() {
        if(isset($this->attributes['area']{0})){
            return Attribute::pattern(Yii::app()->params['attributevalues']['desc'], $this->attributes['area']);
        }
        
        return Attribute::pattern(Yii::app()->params['attributevalues']['desc'], $this->attributes['city']);
    }

    public function getSkuPrices($price) {
        if (!isset($this->attributes['skuQuantities']{0}))
            return '';

        $input = array();
        $skus = explode(',', $this->attributes['skuQuantities']);
        $result = array_pad($input, count($skus), $price);
        return implode(',', $result);
    }

    public function getSkuOuterIds() {
        if (!isset($this->attributes['skuQuantities']{0}))
            return '';
        $input = array();
        $skus = explode(',', $this->attributes['skuQuantities']);
        $result = array_pad($input, count($skus), '');
        return implode(',', $result);
    }

    public function getSkuImgKeys() {
        $keys = array();

        if (!isset($this->attributes['swatchesCode']{0}))
            return false;

        $codes = explode(',', $this->attributes['swatchesCode']);
        $imgs = explode(',', $this->attributes['images']);

        if (is_array($imgs) && is_array($codes)) {
            foreach ($codes as $key => $code) {
                foreach ($imgs as $img) {
                    if (strripos($imge, $code) !== false) {
                        $keys[] = $key;
                        break;
                    }
                }
            }
        }
        return $keys;
    }

    protected function beforeSave() {

        $this->cost = Rate::getUploadCost($this->userId, $this->productId);

        return parent::beforeSave();
    }

    public function getColorsCode() {
        $codes = array();
        if (!isset($this->attributes['colorProperties']{0}))
            return $codes;

        $colors = explode(",", $this->attributes['colorProperties']);
        foreach ($colors as $color) {
            $colorvalue = explode(";", $color);
            $code = Skuattri::getColorCode($this->attributes['id'], $colorvalue[1]);
            if ($code !== false)
                $codes[$colorvalue[0]] = $code;
        }
        return $codes;
    }

    public function productDesc($productImgs) {

        $desc = '';

        if (!is_array($productImgs))
            return $desc;

        $addinfo = $this->setAddInfo();
        if (isset($addinfo{0})) {
            $desc .= CHtml::openTag('div',array('style' => 'background-color: #FFF;'));
            $desc .= CHtml::openTag('div',array('style' => 'border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;'));
            $desc .= CHtml::openTag('h3', array('style' => 'color:#fff;margin: 5px;')) . '商家说明</h3>';
            $desc .= CHtml::closeTag('div');
            $desc .= "<p>{$addinfo}</p>";
            $desc .= CHtml::closeTag('div');
        }

        $desc .= CHtml::openTag('div',array('style' => 'background-color: #FFF;'));
            $desc .= CHtml::openTag('div',array('style' => 'border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;'));
            $desc .= CHtml::openTag('h3', array('style' => 'color:#fff;margin: 5px;')) . '商品展示</h3>';
            $desc .= CHtml::closeTag('div');
        foreach ($productImgs as $codes) {
            foreach ($codes as $img) {
                $desc .= "<p align='center'>" . CHtml::image($img, '', array('border' => 0, 'style' => 'max-width:790px')) . "</p>";
            }
        }
        $desc .= CHtml::closeTag('div');

        
        if (isset($this->attributes['desc']{0})) {
            $desc .= CHtml::openTag('div',array('style' => 'background-color: #FFF;'));
            $desc .= CHtml::openTag('div',array('style' => 'border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;'));
            $desc .= CHtml::openTag('h3', array('style' => 'color:#fff;margin: 5px;')) . '商品信息</h3>';
            $desc .= CHtml::closeTag('div');
            $desc .= "<p>{$this->attributes['desc']}</p>";
            $desc .= CHtml::closeTag('div');
        }

        if (isset($this->attributes['details']{0})) {
            $desc .= CHtml::openTag('div',array('style' => 'background-color: #FFF;'));
            $desc .= CHtml::openTag('div',array('style' => 'border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;'));
            $desc .= CHtml::openTag('h3', array('style' => 'color:#fff;margin: 5px;')) . '商品细节</h3>';
            $desc .= CHtml::closeTag('div');
            $desc .= "<p>{$this->attributes['details']}</p>";
            $desc .= CHtml::closeTag('div');
        }

        if (isset($this->attributes['designer']{0})) {
            $desc .= CHtml::openTag('div',array('style' => 'background-color: #FFF;'));
            $desc .= CHtml::openTag('div',array('style' => 'border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;'));
            $desc .= CHtml::openTag('h3', array('style' => 'color:#fff;margin: 5px;')) . '设计师</h3>';
            $desc .= CHtml::closeTag('div');
            $desc .= "<p>{$this->attributes['details']}</p>";
            $desc .= CHtml::closeTag('div');
        }

        if (isset($this->attributes['sizeFitContainer']{0})) {
            $desc .= CHtml::openTag('div',array('style' => 'background-color: #FFF;'));
            $desc .= CHtml::openTag('div',array('style' => 'border:1px solid #E5E6E9;border-top-left-radius: 8px;border-top-right-radius:8px;background-color: #3B5999;'));
            $desc .= CHtml::openTag('h3', array('style' => 'color:#fff;margin: 5px;')) . '尺寸描述</h3>';
            $desc .= CHtml::closeTag('div');
            $desc .= "<p>{$this->attributes['sizeFitContainer']}</p>";
            $desc .= CHtml::closeTag('div');
        }

        return $desc;
    }

}

?>
