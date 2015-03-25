<?php
/**
 * This is the model class for table "tbl_product".
 *
 * The followings are the available columns in table 'tbl_product':
 * 2015/03/12
 * The followings are the available model relations:
 * @property TblProductType $type0
 */
class Product extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Product the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function saveSku($sku) {
        $price = $this->price;

        Sku::saveSku($this->id, $sku, $price);
    }

    public function saveColorAttri($skuattri) {
        Skuattri::saveSkuAttri($this->id, Skuattri::COLOR, $skuattri);
    }

    public function saveSizeAttri($skuattri) {
        Skuattri::saveSkuAttri($this->id, Skuattri::SIZE, $skuattri);
    }

    public function saveProductImages($images) {
        assert(isset($this->id{0}));
        ProductImages::saveImages($this->id, $images);
    }

    public function getFullTitle() {
        
        $title = $this->brandName . ' ' . $this->productTitle;
        
        $title = stripcslashes($title);
        $title =CHtml::decode($title);

        if(isset($this->area{0})){
            $title = Attribute::patternTitle($title, $this->area);
        }else{
            $title = Attribute::patternTitle($title, $this->city);
        }
        
        if ($this->percentOff > 0 && $this->percentOff != 100)
            $title = Attribute::patternPercentOff(Yii::app()->params['attributevalues']['offer'], $title, $this->percentOff);
        return $title;
    }

    public static function getProductFullTitle($brandName, $productTitle, $percentOff, $city) {
        $title = $brandName . ' ' . $productTitle;
        $title = Attribute::patternTitle($title, $city);
        if ($percentOff > 0 && $percentOff != 100)
            $title = Attribute::patternPercentOff(Yii::app()->params['attributevalues']['offer'], $title, $percentOff);
        return $title;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_product';
    }

    public function setAddInfo() {
        if(isset($this->area{0})){
            return Attribute::pattern(Yii::app()->params['attributevalues']['desc'], $this->area);
        }
        return Attribute::pattern(Yii::app()->params['attributevalues']['desc'], $this->city);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('percentOff', 'numerical', 'integerOnly' => true),
            array('id, price, brandName, type, productTitle, station', 'length', 'max' => 50),
            array('unit,city', 'length', 'max' => 10),
            array('details, designer, sizeFitContainer', 'length', 'max' => 65000),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('weight,freight,station,area', 'safe'),
            array('unit, city, station, pid, originalPrice, alternative, 
                weight, desc, upload, type,area,
                stock, url, updateTime, id, brandName, productTitle, price, originalPrice, 
			       percentOff, details, designer', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    protected function beforeSave() {

        $this->updateTime = Functions::getNow();

        return parent::beforeSave();
    }

    protected function afterFind(){
        $this->brandName = str_replace(',', '', $this->brandName);
        parent::afterFind();
    }
    /**
     * 成功删除商品后，删除其图片
     */
    protected function afterDelete() {
        parent::afterDelete();

        ProductImages::deleteFromPid($this->id);
        Sku::deleteFromPid($this->id);
        Skuattri::deleteAllPid($this->id);
        ProductProperty::deleteFromPid($this->id);

        $rootPath = getcwd() . '/product/' . $this->id . '/';

        Yii::import('lib.Functions');

        Functions::delDirAndFile($rootPath);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'area'=>'area',
            'station' => 'station',
            'freight' => 'freight',
            'weight' => '重量',
            'type' => 'type',
            'unit' => 'unit',
            'pid' => 'pid',
            'id' => '货号',
            'brandName' => '品牌',
            'productTitle' => '商品名',
            'price' => '价格',
            'originalPrice' => '原始价格',
            'percentOff' => 'Percent Off',
            'details' => 'Details',
            'designer' => 'Designer',
            'url' => 'url',
            'updateTime' => '更新日期',
            'stock' => '货存',
            'upload' => '上传次数',
            'desc' => 'desc',
            'alternative' => 'alternative',
            'city' => '货源',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        if (isset($this->price{0}))
            $criteria->condition = "price <= " . $this->price;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('brandName', $this->brandName, true);
        $criteria->compare('productTitle', $this->productTitle, true);
        $criteria->compare('originalPrice', $this->originalPrice, true);
        $criteria->compare('percentOff', $this->percentOff);
        $criteria->compare('sizes', $this->sizes, true);
        $criteria->compare('details', $this->details, true);
        $criteria->compare('designer', $this->designer, true);

        $criteria->order = 'updateTime DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getSupplyDesc() {
        $supply = Supply::model()->findByPk($this->supply);
        if ($supply === NULL)
            return $supply->state . ' ' . $supply->city;

        return '';
    }

    //判断是否当前用户已经上传
    public function isUploadProduct() {
        $uploadhistory = Uploadhistory::model()->findByAttributes(array('productId' => $this->id, 'userId' => Yii::app()->user->getId()));

        return $uploadhistory !== NULL;
    }

    public function showRealPriceDes() {
        $sContent = "<span class='label badge-success'>{$this->unit} {$this->price}</span>";
        if (isset($this->originalPrice{0}))
            $sContent .= "<span class='label'>{$this->unit} {$this->originalPrice}</span>";
        return $sContent;
    }

    public function showPriceDes() {
        $price = ceil(ExchangeRate::countPriceRate($this->price, $this->percentOff, $this->unit, $this->station));
        $originalPrice = ceil(ExchangeRate::countPriceRate($this->originalPrice, $this->percentOff, $this->unit, $this->station));
        $sContent = "<span class='label badge-success'>￥{$price}</span>";
        if (isset($originalPrice{0}))
            $sContent .= "<span class='label'>￥{$originalPrice}</span>";
        return $sContent;
    }

    public function getProductOperate() {
        if (Yii::app()->user->isGuest)
            return '您无权限进行操作';

        $sContent .= CHtml::link('上传淘宝', array('product/upload', 'id' => $this->id), array('id' => "upload", 'class' => 'btn',));

        return $sContent;
    }

    public function getUrlLink() {
        return CHtml::link('查看来源', $this->url, array('target' => '_blank', 'class' => 'btn'));
    }

    public function getSizeArray() {
        return Skuattri::getSizes($this->id);
    }

    public function getSwatchesArray() {
        return Skuattri::getColors($this->id);
    }

    public function showSize() {
        $sizes = Skuattri::getSizes($this->id);
        $sContent = '';

        foreach ($sizes as $size) {
            $sContent .= '<a class="label label_on" title=' . $size['value'] . ' sizeid=' . $size['code'] . '>' . $size['value'] . '</a>';
        }
        return $sContent;
    }

    public function showTaobaoPriceDes() {
        $cost = Express::getCost($this->station, $this->unit, $this->price);
        //$freight = ProductProperty::getFreight($this->id);
        $freight = $this->freight;
        $price = ExchangeRate::countPriceRate($this->price, $this->percentOff, $this->unit, $this->station);
        $total = ceil((float) $cost + (float) $freight + (float) $price);


        $sContent = "<span class='label label-info'>国际运费({$cost})+原价({$price})+附加运费({$freight})</span>";
        $sContent .= "<p><span class='label label-success'>￥{$total}</span></p>";

        return $sContent;
    }

    public function countTaobaoPriceDes($weight) {
        $cost = Express::getCost($this->station, $this->unit, $this->price);
        $weightCost = Attribute::countWeightCost($weight);
        $price = ExchangeRate::countPriceRate($this->price, $this->percentOff, $this->unit, $this->station);
        $total = ceil((float) $cost + (float) $weightCost + (float) $price);


        $sContent = "<span class='label label-info'>国际运费({$cost})+重量运费({$weightCost})+原价({$price})</span>";
        $sContent .= "<p><span class='label label-success'>￥{$total}</span></p>";

        return $sContent;
    }

    public function countTaobaoPriceDesFromFreight($freight) {
        $cost = Express::getCost($this->station, $this->unit, $this->price);
        $price = ExchangeRate::countPriceRate($this->price, $this->percentOff, $this->unit, $this->station);
        $total = ceil((float) $cost + (float) $freight + (float) $price);

        $sContent = "<span class='label label-info'>国际运费({$cost})+原价({$price})+附加运费({$freight})</span>";
        $sContent .= "<p><span class='label label-success'>￥{$total}</span></p>";

        return $sContent;
    }

    public function checkStock($color, $size) {
        if (!isset($color{0}) || !isset($size{0}))
            return 0;

        $swatches = explode("|", $this->swatches);
        $sizes = explode("|", $this->sizes);
        $stock = explode(",", $this->stock);

        $ckey = array_search($color, $swatches);
        if ($ckey === false)
            return 0;

        $skey = array_search($size, $sizes);
        if ($skey === false)
            return 0;

        $index = $ckey * count($sizes) + $skey;
        if (isset($stock[$index]))
            return $stock[$index];

        return 0;
    }

    public function getSwatche($href = '#') {
        $colors = Skuattri::getColors($this->id);

        $string = '';
        foreach ($colors as $color) {
            $string .= "<a class='color' colorid='{$color['code']}' title='{$color['value']}' href='{$href}'>";
            if (isset($color['image']{0}))
                $string .= CHtml::image(Yii::app()->baseUrl . '/product/' . $this->id . '/' . $color['image'], '', array('style' => 'width:18px;height:18px;'));
            else
                $string .= $color['value'];
            $string .= '</a>';
        }

        return $string;
    }

    public function showSwatche() {

        $string = $this->getSwatche();

        ///////////////////////////关联/////////////////////////////////
        $alternatives = explode(";", $this->alternative);
        if (isset($this->alternative{0})) {
            foreach ($alternatives as $pid) {
                $product = $this->findAlternativesProdect($this->station . $pid);
                if ($product !== null) {
                    $href = CHtml::normalizeUrl(array('product/view/id/' . $product->id));
                    $string .= ' ' . $product->getSwatche($href);
                }
            }
        }
        return $string;
    }

    //得到关联商品
    public function findAlternativesProdect($id) {
        return Product::model()->findByAttributes(array('id' => $id));
    }

    public function getStock() {
        $stockes = explode(",", $this->stock);
        $stock = array_sum($stockes);

        if ($stock <= 0)
            return "<span class='badge badge-warning'>{$stock}</span>";
        else
            return "<span class='badge badge-info'>{$stock}</span>";
    }

    protected function getProductImg($data, $row) {
        $arryImgs = explode("|", $data->images);
        return CHtml::image(Yii::app()->baseUrl . '/product/' . $arryImgs[0], '', array('width' => 50, 'height' => 50));
    }

    public function getProductBigImg() {
        return CHtml::image(Yii::app()->baseUrl . '/product/' . $this->id . '/' . ProductImages::getFirstImage($this->id), '', array('width' => 300, 'height' => 350, 'style' => 'vertical-align: middle;'));
    }

    public function getFirstImg() {
        $img = ProductImages::getFirstImage($this->id);
        if (isset($img{0}))
            return CHtml::image(Yii::app()->baseUrl . '/product/' . $this->id . '/' . $img, '', array('width' => "86px", 'height' => "100px"));
        else {
            return CHtml::image(Yii::app()->baseUrl . '/img/loading.gif', '', array('width' => "32px", 'height' => "32px"));
        }
    }

    public function getImages() {
        return ProductImages::getImages($this->id);
    }

    public function getImagesDesc() {
        $images = ProductImages::getImages($this->id);
        $string = '';
        foreach ($images as $image) {
            $string .= '<p align=\'center\'>';
            $string .= CHtml::image(Yii::app()->baseUrl . '/product/' . $this->id . '/' . $image['image'], '', array('border' => 0, 'style' => 'max-width:600px'));
            $string .= '</p>';
        }

        return $string;
    }

    public function getloadFirstImg() {
        $loadimg = Yii::app()->baseUrl . '/img/loading.gif';
        $noneimg = Yii::app()->baseUrl . '/img/none.gif';
        return CHtml::image($noneimg, '', array(
                    'width' => "151px", 'height' => "216px", 'class' => 'scrollLoading',
                    'data-url' => Yii::app()->baseUrl . '/product/' . $this->id . '/' . ProductImages::getFirstImage($this->id),
                    'style' => "background:url({$loadimg}) no-repeat center;"));
    }

    public function getTitle() {
        return $this->brandName . ' ' . $this->productTitle;
    }
    
    public function saveTags($tags) {
        $count = count($tags);
        
        if(is_array($tags) && $count>0){
            foreach ($tags as $key=>$value) {
                if($key-1 < 0){
                    ProductType::createType($key, $value);
                }else{
                    ProductType::createType($key, $value,$tags[$key-1]);
                }
            }
        }
        $this->type = $tags[$count-1];
    }

    public static function getProductInfo($id) {
        return Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('tbl_product')
                        ->where('id=:id', array(':id' => $id))
                        ->queryRow();
    }

    public static function getProductUrl($id) {
        $row = Yii::app()->db->createCommand()
                ->select('url')
                ->from('tbl_product')
                ->where('id=:id', array(':id' => $id))
                ->queryRow();
        if ($row === false)
            return false;
        return $row['url'];
    }

}