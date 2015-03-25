<?php

/**
 * This is the model class for table "tbl_skuattri".
 *
 * The followings are the available columns in table 'tbl_skuattri':
 * @property string $code
 * @property string $value
 * @property string $image
 * @property integer $skuId
 * @property string $pid
 */
class Skuattri extends CActiveRecord {

    CONST COLOR = 0;
    CONST SIZE = 1;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Skuattri the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_skuattri';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('skuId', 'numerical', 'integerOnly' => true),
            array('code, value', 'length', 'max' => 20),
            array('image, pid', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('code, value, image, skuId, pid', 'safe', 'on' => 'search'),
        );
    }

    public static function saveSkuAttri($pid, $skuid, $attri) {
        Yii::import('lib.functions');
        Skuattri::deleteFromPid($pid, $skuid);
        if(is_array($attri)){
            foreach ($attri as $obj) {
                $skuattri = new Skuattri;
                $skuattri->pid = $pid;
                $skuattri->skuId = $skuid;
                $skuattri->code = $obj->code;
                $skuattri->value = $obj->name;
                $skuattri->image = Functions::getImageName($obj->image);     
                if(!$skuattri->save())
                    throw new CException('saveSkuAttri error'.$skuid);
            }
        }
    }
    
    private static function deleteFromPid($pid, $skuid) {
        Yii::app()->db->createCommand()
                ->delete('tbl_skuattri', 'pid=:pid AND skuId=:skuId', array(':pid'=>$pid, ':skuId'=>$skuid));
    }
    
    public static function deleteAllPid($pid) {
        Yii::app()->db->createCommand()
                ->delete('tbl_skuattri', 'pid=:pid', array(':pid'=>$pid));
    }
    
    public static function getColors($pid){
  
        return Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('tbl_skuattri')
                        ->where('pid=:pid AND skuId=:skuId', array(':pid'=>$pid, ':skuId'=>  self::COLOR))
                        ->queryAll();
    
    }
    
    public static function getColorCode($pid, $value){
  
        $row = Yii::app()->db->createCommand()
                        ->select('code')
                        ->from('tbl_skuattri')
                        ->where('pid=:pid AND value=:value AND skuId=0 ', array(':pid'=>$pid, ':value'=>$value))
                        ->queryRow();
        if($row !== false)  return $row['code'];
        
        return false;
    }
    
    public static function getSizes($pid){
  
        return Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('tbl_skuattri')
                        ->where('pid=:pid AND skuId=:skuId', array(':pid'=>$pid, ':skuId'=>  self::SIZE))
                        ->queryAll();
    
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
            'code' => 'Code',
            'value' => 'Value',
            'image' => 'Image',
            'skuId' => 'Sku',
            'pid' => 'Pid',
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

        $criteria->compare('code', $this->code, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('skuId', $this->skuId);
        $criteria->compare('pid', $this->pid, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}