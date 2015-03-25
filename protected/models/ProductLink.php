<?php

/**
 * This is the model class for table "tbl_product_link".
 *
 * The followings are the available columns in table 'tbl_product_link':
 * @property integer $task_id
 * @property integer $product_tpye_id
 * @property string $url
 * @property integer $finish
 *
 * The followings are the available model relations:
 * @property TblProductType $productTpye
 * @property TblTask $task
 */
class ProductLink extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductLink the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_product_link';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url', 'length', 'max' => 255),
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
            'url' => '地址',
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

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 1000000,
            ),
        ));
    }

    public static function saveProductLink($url) {
        if (isset($url{0})) {
            Yii::app()->db->createCommand()->insert('tbl_product_link', array('url'=>$url));
        }
    }
    
    public static function searchProductLink($count=150) {
            return Yii::app()->db->createCommand()
                    ->select('url')
                    ->from('tbl_product_link')
                    ->limit($count)
                    ->queryAll();
    }
    
    public static function deleteProductLink($url) {
        if (isset($url{0})) {
            Yii::app()->db->createCommand()->delete('tbl_product_link', 'url=:url', array(':url'=>$url));
        }
    }

}