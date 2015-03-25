<?php

/**
 * This is the model class for table "tbl_user_product".
 *
 * The followings are the available columns in table 'tbl_uploadhistory':
 * @property integer $id
 * @property string $uploadTime
 * @property integer $userId
 * @property string $productId
 * @property string $num_iid
 *
 * The followings are the available model relations:
 * @property TblProduct $product
 * @property TblUser $user
 */
class UserProduct extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Uploadhistory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_user_product';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('pid, uid', 'required'),
            array('uid', 'numerical', 'integerOnly' => true),
            array('pid, num_iid', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('pid, uid, num_iid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'product' => array(self::BELONGS_TO, 'TblProduct', 'pid'),
            'user' => array(self::BELONGS_TO, 'TblUser', 'uid'),
        );
    }

    //保存上传历史 mender stephen 2013-06-10
    public static function saveUploadHistory($id, $num_iid, $url) {
        Yii::import('lib.taobao.Functions');
        $uploadhistory = Uploadhistory::model()->findByAttributes(array('productId' => $id, 'num_iid' => $num_iid, 'userId' => Yii::app()->user->id));
        if ($uploadhistory === NULL) {
            $uploadhistory = new Uploadhistory;
            $uploadhistory->userId = Yii::app()->user->id;
            $uploadhistory->productId = $id;
            $uploadhistory->num_iid = $num_iid;       
            if($url !== false)
                $uploadhistory->url = $url;  
        }
        $uploadhistory->uploadTime = Functions::getNow();
       
        if(!$uploadhistory->save())
            Functions::message ($uploadhistory->getErrors());
    }
    
    //保存上传历史 mender stephen 2013-06-10
    public static function updateUploadHistory($id, $num_iid) {
        Yii::import('lib.taobao.Functions');
        $uploadhistory = Uploadhistory::model()->findByAttributes(array('productId' => $id, 'num_iid' => $num_iid, 'userId' => Yii::app()->user->id));
        if ($uploadhistory !== NULL) {
            $uploadhistory->uploadTime = Functions::getNow();
            if(!$uploadhistory->save())
                Functions::message ($uploadhistory->getErrors());
            return $uploadhistory->url;
        }
        
       
        return false;
    }
    
    public static function saveUserProduct($pid) {
        $command = Yii::app()->db->createCommand('call pro_insertUserProduct(:userId,:pid)');
        $command->execute(array(':userId'=>Yii::app()->user->id, ':pid'=>$pid));
    }
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'pid' => 'pid',
            'uploadTime' => '上传时间',
            'uid' => 'uid',
            'num_iid' => '淘宝商品号',
        );
    }

    public static function isUpload($pid) {
        $row = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('tbl_uploadhistory')
                        ->where('productId=:pid AND userId=:userID', array(':pid'=>$pid, ':userID'=>Yii::app()->user->id))
                        ->queryRow();
        if($row === false) return false;
        
        return true;
    }
    
    public static function fideNumIid($pid) {
        $row = Yii::app()->db->createCommand()
                        ->select('num_iid')
                        ->from('tbl_uploadhistory')
                        ->where('productId=:pid AND userId=:userID', array(':pid'=>$pid, ':userID'=>Yii::app()->user->id))
                        ->queryRow();
        if($row === false) return false;
        
        return $row['num_iid'];
    }
    
    public static function isUploaded($num_iid, $userId) {
        $row = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('tbl_uploadhistory')
                        ->where('num_iid=:num_iid AND userId=:userID', array(':num_iid'=>$num_iid, ':userID'=>$userId))
                        ->queryRow();
        if($row === false) return false;
        
        return true;
    }

    public static function deleteByPid($pid){
        Yii::app()->db->createCommand()
                ->delete('tbl_uploadhistory', 'productId=:pid AND userId=:userID', array(':pid'=>$pid, ':userID'=>Yii::app()->user->id));
    }
    
    public static function deleteByUserId($userid){
        Yii::app()->db->createCommand()
                ->delete('tbl_uploadhistory',  'userId=:userID', array(':userID'=>$userid));
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
        $criteria->compare('uploadTime', $this->uploadTime, true);
        $criteria->compare('userId', $this->userId);
        $criteria->compare('productId', $this->productId, true);
        $criteria->compare('num_iid', $this->num_iid, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}