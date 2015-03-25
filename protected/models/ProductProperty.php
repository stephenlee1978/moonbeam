<?php
/**
 * This is the model class for table "tbl_product_property".
 * 20140806
 * The followings are the available columns in table 'tbl_product_property':
 * @property string $userID
 * @property string $pid
 * @property string $itemCats
 * @property string $approveStatus
 * @property string $sellercats
 * @property string $sellerPath
 * @property string $skuQuantities
 * @property string $subTitle
 * @property string $inputStr
 * @property string $skuProperties
 * @property string $pidPath
 * @property string $inputPids
 * @property string $propertyAlias
 * @property string $props
 */
class ProductProperty extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductProperty the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

  
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_product_property';
    }

    public static function updateApproveStatus($status, $pid){
        Yii::app()->db->createCommand()
                ->update('tbl_product_property', array('approveStatus'=>$status), 'productId=:pid AND userID=:userID', array(':pid'=>$pid, ':userID'=>Yii::app()->user->id));
    }
    
    public static function deleteFromPid($pid) {
        Yii::app()->db->createCommand()
                ->delete('tbl_product_property', 'productId=:productId', array(':productId'=>$pid));
    }
    
    public static function getObject($pid) {
        $property = ProductProperty::model()->find('userID=:userID AND productId=:pid', array(':userID' => Yii::app()->user->id, ':pid' => $pid));
        if ($property === null)
            $property = new ProductProperty;
        return $property;
    }

    public static function getPropertynfo($id) {
        return Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('tbl_product_property')
                        ->where('productId=:pid AND userID=:userID', array(':pid' => $id, ':userID' => Yii::app()->user->id))
                        ->queryRow();
    }
    
    public static function createInProductIds($ids) {
        $property = $model = ProductProperty::model()->findByAttributes(array("productId"=>$ids), 'userID=:userID', array(':userID'=>Yii::app()->user->id));
        //$property = ProductProperty::model()->find("userID=:userID AND productId=:productId", array(':userID' => Yii::app()->user->id, ':productId'=>$ids));
        if ($property === null)
            $property = new ProductProperty;
        return $property;
    }


    public static function getWeight($id) {
        $row = Yii::app()->db->createCommand()
                        ->select('weight')
                        ->from('tbl_product_property')
                        ->where('productId=:pid AND userID=:userID', array(':pid' => $id, ':userID' => Yii::app()->user->id))
                        ->queryRow();
        if($row === false) return 0;
        
        return $row['weight'];
    }
    
    public static function getFreight($id) {
        $row = Yii::app()->db->createCommand()
                        ->select('freight')
                        ->from('tbl_product_property')
                        ->where('productId=:pid AND userID=:userID', array(':pid' => $id, ':userID' => Yii::app()->user->id))
                        ->queryRow();
        if($row === false) return 0;
        
        return $row['freight'];
    }

    public function getTilteLenght() {
        if (!isset($this->subTitle{0}))
            return 0;

        return (strlen($this->subTitle) + mb_strlen($this->subTitle, 'UTF8')) / 2;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('freight', 'length', 'max' => 10),
            array('weight', 'length', 'max' => 11),
            array('weight','default','value'=>0), 
            array('colorProperties, sizeProperties', 'length', 'max' => 1024),
            array('upid', 'length', 'max' => 60),
            array('productId', 'length', 'max' => 50),
            array('itemCats', 'length', 'max' => 20),
            array('subTitle', 'length', 'max' => 100),
            array('upid, userID,approveStatus, sellercats, sellerPath, pidPath', 'length', 'max' => 255),
            array('colorProperties, sizeProperties, weight, sizeProperties, inputStr, inputPids, propertyAlias, props', 'safe'),
            array('userID, freight, productId, approveStatus, sellercats, sizeProperties, sellerPath, inputStr, inputPids, propertyAlias, props', 'safe', 'on' => 'load'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('userID, weight, freight, productId, itemCats, approveStatus, sizeProperties, sellercats, sellerPath, subTitle, inputStr, pidPath, inputPids, propertyAlias, props', 'safe', 'on' => 'search'),
        );
    }
    
    public function validate($attributes = null, $clearErrors = true) {
        if(!isset($this->subTitle{0})) {
            $this->addError('subTitle', '未设置标题！');
            return false;
        }
        
        Yii::import('lib.Functions');
        if(Functions::isOverSixTeenWord($this->subTitle)){
            $this->addError('subTitle', '商品标题超出60个字符！');
            return false;
        }
        
        return parent::validate($attributes, $clearErrors);
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

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'upid'=>'upid',
            'weight' => '重量',
            'userID' => 'User',
            'productId' => 'productId',
            'itemCats' => '商品分类',
            'approveStatus' => 'Approve Status',
            'sellercats' => 'Sellercats',
            'sellerPath' => 'Seller Path',
            'subTitle' => '商品标题',
            'inputStr' => 'Input Str',
            'pidPath' => '类别描述',
            'inputPids' => 'inputPids',
            'propertyAlias' => 'Property Alias',
            'props' => 'Props',
            'colorProperties' => 'colorProperties',
            'sizeProperties' => 'sizeProperties',
            'freight'=>'freight'
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

        $criteria->compare('userID', $this->userID, true);
        $criteria->compare('itemCats', $this->itemCats, true);
        $criteria->compare('approveStatus', $this->approveStatus, true);
        $criteria->compare('sellercats', $this->sellercats, true);
        $criteria->compare('sellerPath', $this->sellerPath, true);
        $criteria->compare('subTitle', $this->subTitle, true);
        $criteria->compare('inputStr', $this->inputStr, true);
        $criteria->compare('pidPath', $this->pidPath, true);
        $criteria->compare('inputPids', $this->inputPids, true);
        $criteria->compare('propertyAlias', $this->propertyAlias, true);
        $criteria->compare('props', $this->props, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}