<?php

/**
 * This is the model class for table "tbl_file".
 *
 * The followings are the available columns in table 'tbl_file':
 * @property integer $id
 * @property string $fName
 * @property integer $fOrder
 * @property string $URL
 * @property string $size
 * @property string $createTime
 * @property string $updatedTime
 */
class File extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return File the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_file';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fOrder', 'numerical', 'integerOnly' => true),
            array('fName, URL', 'length', 'max' => 255),
            array('size', 'length', 'max' => 20),
            array('createTime, updatedTime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, fName, fOrder, URL, size, createTime, updatedTime', 'safe', 'on' => 'search'),
        );
    }

    public static function getFileUrlFormPk($pk){
        $row = Yii::app()->db->createCommand()
                ->select('URL')
                ->from('tbl_file')
                ->where('id=:id', array(':id'=>$pk))
                ->queryRow();
        if (isset($row['URL'])) {
            return $row['URL'];
        }
        return '';
    }
    
    public static function getImageById($pk) {

        $model = File::model()->findByPk($pk);
        if ($model === null)
            return '';

        return $model->URL;
    }
    
    public static function saveImage($path){
        Yii::import('application.lib.Functions');
        
        if(isset($path{0})){
            $file = new File;
            $file->URL = $path;
            $file->createTime = Functions::getNow();
            if($file->save()) return $file->id;
        }
        return false;
    }

    

    public static function deleteImages($pk) {
        $model = File::model()->findByPk($pk);
        if ($model !== null) {
            Yii::import('application.lib.Functions');
            Functions::deleteImage($model->URL);
            $model->delete();
            return true;
        }
        return false;
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
            'id' => 'ID',
            'fName' => 'F Name',
            'fOrder' => 'F Order',
            'URL' => 'Url',
            'size' => 'Size',
            'createTime' => 'Create Time',
            'updatedTime' => 'Updated Time',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('fName', $this->fName, true);
        $criteria->compare('fOrder', $this->fOrder);
        $criteria->compare('URL', $this->URL, true);
        $criteria->compare('size', $this->size, true);
        $criteria->compare('createTime', $this->createTime, true);
        $criteria->compare('updatedTime', $this->updatedTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}