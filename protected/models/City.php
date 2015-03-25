<?php

/**
 * This is the model class for table "tbl_city".
 *
 * The followings are the available columns in table 'tbl_city':
 */
class City extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Supply the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_city';
    }

    public static function getIdArray() {
        $ids = array();

        $rowdata = Yii::app()->db->createCommand()
                ->from('tbl_city')
                ->select('id')
                ->queryAll();
        foreach ($rowdata as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }
    
    public static function getCityByPk($pk) {

        $row = Yii::app()->db->createCommand()
                ->from('tbl_city')
                ->select('city')
                ->where('id=:id', array('id'=>$pk))
                ->queryRow();
        
        if (isset($row['city'])) {
            return $row['city'];
        }
        return '未知城市';
    }
    
    public static function getCityPkByCity($city){
        $row = Yii::app()->db->createCommand()
                ->from('tbl_city')
                ->select('id')
                ->where('city=:city', array('city'=>$city))
                ->queryRow();
        
        if ($row!==null && isset($row['id'])) {
            return $row['id'];
        }
        return 0;
    }
    
    public static function getStatePkByCity($city){
        $row = Yii::app()->db->createCommand()
                ->from('tbl_city')
                ->select('state')
                ->where('city=:city', array('city'=>$city))
                ->queryRow();
        
        if (isset($row['state'])) {
            return $row['state'];
        }
        return '';
    }

        /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('state, city', 'required', 'message'=>'{attribute} 不能为空.'),
            array('state, city', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,state, city', 'safe', 'on' => 'search'),
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
            'state' => '国家',
            'city' => '城市',
            'id' => 'id',
        );
    }

    public static function getFare($city) {
        try {
            $command = Yii::app()->db->createCommand()
                    ->select('fare')
                    ->from('tbl_city')
                    ->where('city=:city', array(':city' => $city));
            $row = $command->queryRow();
            return $row['fare'];
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return 0;
    }

    public static function getMath($city) {
        try {
            $command = Yii::app()->db->createCommand()
                    ->select('math')
                    ->from('tbl_city')
                    ->where('city=:city', array(':city' => $city));
            $row = $command->queryRow();
            return $row['math'];
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
        return '';
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('state', $this->state, true);
        $criteria->compare('city', $this->city, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}