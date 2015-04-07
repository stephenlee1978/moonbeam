<?php
/**
 * This is the model class for table "tbl_attribute".
 *
 * The followings are the available columns in table 'tbl_attribute':
 * 2015/03/30 stephenlee
 */
class Attribute extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Attribute the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_attribute';
    }

    public static function excuteCommand($sels, $command){
        if($command == 'delAll'){
            foreach ($sels as $id) {
                Attribute::deleteAttribute($id);
            }
        }
    }
    
    public static function deleteAttribute($id){
       return Yii::app()->db->createCommand()
               ->delete('tbl_attribute', 'id=:id', array(':id'=>$id));
    }
    
    public static function deleteUserAttribute($userid){
       return Yii::app()->db->createCommand()
               ->delete('tbl_attribute', 'userID=:userID', array(':userID'=>$userid));
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userID, property, pattern', 'required', 'message'=>'{attribute} 不能为空.'),
            array('id, userID,property', 'numerical', 'integerOnly' => true),
            array('pattern', 'length', 'max' =>65535),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, property, pattern, userID', 'safe', 'on' => 'search'),
        );
    }
    
    public static function countWeightCost($weight) {
        
        $pattern = self::findPattern(Yii::app()->params['attributevalues']['weight']);
      
        if($pattern !== false && isset($pattern{0})){         
            Yii::import('lib.Functions');
            return Functions::math($weight, $pattern);
        }
        return 0;
    }
    
    private static function findPattern($property){
        $row = Yii::app()->db->createCommand()
                        ->select('pattern')
                        ->from('tbl_attribute')
                        ->where('userID=:userId AND property=:property', 
                array(':userId' => Yii::app()->user->id, ':property' => $property))
                       ->queryRow();
        if($row === false) return false;
        
        return $row['pattern'];
    }
    
    public static function patternPercentOff($property, $value, $percentOff) {

        if(!isset(Yii::app()->user->id)) return false;

        $pattern = self::findPattern($property);

        if($pattern !== false && isset($pattern{0})){ 
            $pattern = str_replace('{off}', $percentOff/10, $pattern);
            return str_replace('{value}', $value, $pattern);
        }
        return false;
    }
    
    public static function pattern($property, $value) {

        if(!isset(Yii::app()->user->id)) return false;

        $pattern = self::findPattern($property);

        if($pattern !== false && isset($pattern{0})){ 
            Yii::import('lib.Functions');
            return Functions::pattern($value, $pattern);
        }
        return false;
    }
    
    public static function patternTitle($title, $city) {

        if(!isset(Yii::app()->user->id)) return false;

        $fullTitle = '';
        $pattern = self::findPattern(Yii::app()->params['attributevalues']['title']);

        if($pattern !== false && isset($pattern{0})){ 
            $fullTitle = $pattern;
            Yii::log($city);
            Yii::log($fullTitle);
            $fullTitle = str_replace('{area}', $city, $fullTitle);
            return str_replace('{value}', $title, $fullTitle);
        }
        return false;
    }
    
    public function getUserName($data, $row) {
        $user = User::Model()->findByPk($data->userID);
        return $user->username;
    }
    
    public function getPropertyName($data, $row) {
        $attributes = Yii::app()->params['attributes'];
        if(isset($attributes[$data->property])) return $attributes[$data->property];
        
        return '未知属性';
    }

    public function saveAll() {
        $userdata = array();
        $userdata[] = $this->userID;

        if ($this->userID == 0) {//多用户同时建立
            $userdata = User::getIdArray();
        }

        foreach ($userdata as $userid) {
            $model = Attribute::model()->find('userID=:userId AND property=:property', array(':userId' => $userid, ':property' => $this->property));
                if ($model === null) {
                    $model = new Attribute();
                }
   
                $model->userID = $userid;
                $model->property = $this->property;
                $model->pattern = $this->pattern;
                if (!$model->save())
                    return false;
        }

        return true;
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
            'property' => '对应属性',
            'pattern' => '公式/值',
            'userID' => '对应用户',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('property', $this->property, true);
        $criteria->compare('pattern', $this->pattern, true);
        $criteria->compare('userID', $this->userID, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

}