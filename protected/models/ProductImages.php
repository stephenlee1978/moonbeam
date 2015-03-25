<?php

/**
 * This is the model class for table "tbl_product_images".
 * 2015/03/12
 * The followings are the available columns in table 'tbl_product_images':
 * @property string $code
 * @property string $image
 * @property string $pid
 */
class ProductImages extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductImages the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_product_images';
    }

     public static function deleteFromPid($pid) {
        Yii::app()->db->createCommand()
                ->delete('tbl_product_images', 'pid=:pid', array(':pid'=>$pid));
    }
    
    public static function saveImages($pid, $images) {
        if(is_array($images)){
            ProductImages::deleteFromPid($pid);
            foreach ($images as $key=>$objs) {
                foreach ($objs as $imgkey=>$image) {
                    $obj = new ProductImages;
                    $obj->sort= $imgkey;
                    $obj->pid = $pid;
                    $obj->code = $key;
                    $obj->image = Functions::getImageName($image);    
                    $obj->save();
                }
            }
        }
    }
    
    public static function getFirstImage($pid){
        $row = Yii::app()->db->createCommand()
                        ->select('image')
                        ->from('tbl_product_images')
                        ->where('pid=:pid', array(':pid'=>$pid))
                        ->order('sort')
                        ->queryRow();
        if(isset($row['image']))
            return $row['image'];
        return false;
    }
    
    public static function getImages($pid){
        return Yii::app()->db->createCommand()
                        ->select('image')
                        ->from('tbl_product_images')
                        ->where('pid=:pid', array(':pid'=>$pid))
                        ->order('sort')
                        ->queryAll();
    }
    
    public static function getProductImages($pid){
        return Yii::app()->db->createCommand()
                        ->select('*')
                        ->from('tbl_product_images')
                        ->where('pid=:pid', array(':pid'=>$pid))
                        ->order('sort')
                        ->queryAll();
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('code, image, pid', 'length', 'max' => 50),
            array('image', 'length', 'max' => 100),
            // The following rule is used by search().
            array('sort', 'safe'),
            // Please remove those attributes that should not be searched.
            array('code, image, pid, sort', 'safe', 'on' => 'search'),
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
            'code' => 'Code',
            'image' => 'Image',
            'pid' => 'Pid',
            'sort'=>'sort',
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
        $criteria->compare('image', $this->image, true);
        $criteria->compare('pid', $this->pid, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}