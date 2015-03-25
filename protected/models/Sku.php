<?php

/**
 * This is the model class for table "tbl_sku".
 *
 * The followings are the available columns in table 'tbl_sku':
 * @property string $price
 * @property string $pid
 * @property integer $count
 * @property string $value
 */
class Sku extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Sku the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function deleteFromPid($pid) {
        Yii::app()->db->createCommand()
                ->delete('tbl_sku', 'pid=:pid', array(':pid'=>$pid));
    }
    
    public static function findFromPid($pid, $color, $size) {
        $row = Yii::app()->db->createCommand()
                    ->select('count, price')
                    ->from('tbl_sku')
                    ->where('pid=:pid AND color=:color AND size=:size', array(':pid'=>$pid, ':color'=>$color, ':size'=>$size))
                    ->queryRow();
        return $row;
    }
    
    public static function getMaxPrice($pid){

        $row = Yii::app()->db->createCommand()
                    ->select('max(price) as tprice')
                    ->from('tbl_sku')
                    ->where('pid=:pid', array(':pid'=>$pid))
                    ->queryRow();
        if($row !== false){
            return $row['tprice'];
        }
        return false;
    }


    public static function saveSku($pid, $sku, $price) {
        
        if(is_array($sku)){
            Sku::deleteFromPid($pid);
            foreach ($sku as $obj) {
                $skuobj = new Sku;
                $skuobj->count = $obj->count;
                $skuobj->pid = $pid;
                $skuobj->size = $obj->size;
                $skuobj->color = $obj->color;
                if(isset($obj->price)) 
                    $skuobj->price = $obj->price;
                else
                    $skuobj->price = $price;
                $skuobj->save();
            }
        }
    }
    
    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_sku';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('count', 'numerical', 'integerOnly' => true),
            array('price', 'length', 'max' => 10),
            array('pid,color', 'length', 'max' => 50),
            array('size', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('price, pid, count, size, color', 'safe', 'on' => 'search'),
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

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'price' => 'Price',
            'pid' => 'Pid',
            'count' => 'Count',
            'color' => 'color',
            'size'=>'size',
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

        $criteria->compare('price', $this->price, true);
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('count', $this->count);
        $criteria->compare('size', $this->size, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}