<?php

/**
 * This is the model class for table "tbl_express".
 *
 * The followings are the available columns in table 'tbl_express':
 * @property integer $id
 * @property integer $cityID
 * @property integer $userID
 * @property string $cost
 */
class Express extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Express the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_express';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userID, station,unitID', 'required', 'message' => '{attribute} 不能为空.'),
            array('userID', 'numerical', 'integerOnly' => true),
            array('station', 'length', 'max' => 255),
            array('cost', 'length', 'max' => 10),
            array('maxFree', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, unitID, station userID, cost', 'safe', 'on' => 'search'),
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

    public function getUserName($data, $row) {
        $user = User::Model()->findByPk($data->userID);
        return $user->username;
    }
    
    public static function getCost($station, $unitID, $price) {
        $row = Yii::app()->db->createCommand()
                        ->select('cost,maxFree')
                        ->from('tbl_express')
                        ->where('userID=:userID AND station=:station AND unitID=:unitID', 
                        array(':userID' => Yii::app()->user->id, ':station' =>$station, ':unitID'=>$unitID))
                       ->queryRow();
        if($row === false) return 0;
         
        if(isset($row['maxFree']{0}) && $row['maxFree']>0.00){
            if($price >= $row['maxFree']) return 0;
        }
        return $row['cost'];
    }

    public static function excuteCommand($sels, $command) {
        if ($command == 'delAll') {
            foreach ($sels as $id) {
                Express::deleteExpress($id);
            }
        }
    }

    public static function deleteExpress($id) {
        return Yii::app()->db->createCommand()
                        ->delete('tbl_express', 'id=:id', array(':id' => $id));
    }
    
    public static function deleteUserExpress($userid) {
        return Yii::app()->db->createCommand()
                        ->delete('tbl_express', 'userID=:userID', array(':userID' => $userid));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'station' => '站点',
            'unitID' => '货币',
            'userID' => '用户',
            'maxFree' => '免运费(价格大于等于)',
            'cost' => '运费',
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
        $criteria->compare('station', $this->station);
        $criteria->compare('unitID', $this->station);
        $criteria->compare('maxFree', $this->maxFree, true);
        $criteria->compare('userID', $this->userID);
        $criteria->compare('cost', $this->cost, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function saveAll() {

        $model = Express::model()->find('userID=:userId AND unitID=:unitID AND station=:station', array(':userId' => $this->userID, ':unitID' => $this->unitID, ':station' => $this->station));
        if ($model === null) {
            $model = new Express();
        }
        $model->attributes = $this->attributes;

        if (!$model->save()){
            $this->errors = $model->getErrors();
            return false;
        }

        return true;
    }

}