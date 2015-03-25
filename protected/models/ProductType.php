<?php

/**
 * This is the model class for table "tbl_product_type".
 *
 * The followings are the available columns in table 'tbl_product_type':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property TblProduct[] $tblProducts
 * @property TblTask[] $tblTasks
 */
class ProductType extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductType the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_product_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('level', 'numerical', 'integerOnly' => true),
            array('name,parent_id', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('parent_id, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Products' => array(self::HAS_MANY, 'Product', 'type'),
        );
    }

    public static function createType($level, $name, $parent=NULL) {
        $model = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('tbl_product_type')
                    ->where('name=:name', array(':name' => $name))
                    ->queryRow();
        if($model===false){
            Yii::app()->db->createCommand()->insert('tbl_product_type', array('parent_id'=>$parent,'name'=>$name,'level'=>$level));
        }
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'level'=>'level',
            'parent_id' => '父类ID',
            'name' => '商品类型',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('level', $this->level, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}