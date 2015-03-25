<?php

/**
 * This is the model class for table "tbl_exchange_rate".
 *
 * The followings are the available columns in table 'tbl_exchange_rate':
 * @property integer $id
 * @property string $pattern
 * @property integer $unitID
 * @property integer $userID
 */
class ExchangeRate extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ExchangeRate the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_exchange_rate';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userID', 'numerical', 'integerOnly' => true),
            array('pattern', 'length', 'max' => 255),
            array('unitID,station', 'length', 'max' => 100),
            array('isoff', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, pattern, unitID, userID', 'safe', 'on' => 'search'),
        );
    }

    private static function findPattern($price, $percentOff, $unitID, $station) {
        $row = Yii::app()->db->createCommand()
                        ->select('pattern')
                        ->from('tbl_exchange_rate')
                        ->where('userID=:userId AND unitID=:unitID AND station=:station AND isoff=:percentOff', array(':userId' => Yii::app()->user->id, ':unitID' => $unitID, ':station'=>$station, ':percentOff'=>$percentOff))
                       ->queryRow();
        if($row === false) return 0;
                        
        return $row['pattern'];
    }
    
    public static function countPriceRate($price, $percentOff, $unitID, $station) {

        if (!isset(Yii::app()->user->id))
            return 0;

        $isoff = 0;
        if($percentOff > 0 && $percentOff < 100){
            $isoff = 1;
            Yii::log('percentOff='.$percentOff);
        }
        
        
        $pattern = self::findPattern($price, $isoff, $unitID, $station);

       if($pattern !== false && isset($pattern{0})){ 
            Yii::import('lib.Functions');
            return Functions::math($price, $pattern);
        }
        return $price;
    }

    public function getUserName($data, $row) {
        $user = User::Model()->findByPk($data->userID);
        if ($user === null)
            return '未知用户';
        return $user->username;
    }

    public function getUnitName($data, $row) {
        $unit = Unit::Model()->findByPk($data->unitID);
        return $unit->remark;
    }
    
    public function getIsOff($data, $row) {
        if ($data->isoff == 0) {
            return 'NO';
        }
        return 'YES';
    }

    public static function excuteCommand($sels, $command) {
        if ($command == 'delAll') {
            foreach ($sels as $id) {
                ExchangeRate::deleteExchangeRate($id);
            }
        }
    }

    public static function deleteExchangeRate($id) {
        return Yii::app()->db->createCommand()
                        ->delete('tbl_exchange_rate', 'id=:id', array(':id' => $id));
    }
    
    public static function deleteUserExchangeRate($userid) {
        return Yii::app()->db->createCommand()
                        ->delete('tbl_exchange_rate', 'userID=:userID', array(':userID' => $userid));
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
            'station' => '站点',
            'pattern' => '汇率公式',
            'unitID' => '货币类型',
            'userID' => '用户',
            'isoff'=>'特价类型'
        );
    }

    public static function copyUserConfig($fuserId, $tuserId) {
        $fusers = ExchangeRate::model()->findAll('userID=:userId', array(':userId' => $fuserId));
        foreach ($fusers as $fuser) {
            $model = ExchangeRate::model()->find('userID=:userId AND unitID=:unitID AND station=:station', array(':userId' => $tuserId, ':unitID' => $fuser->unitID, ':station' => $fuser->station));
            if ($model === null) {
                $model = new ExchangeRate();
            }
            $model->attributes = $fuser->attributes;
            $model->userID = $tuserId;
            $model->save();
        }
    }

    public function saveAll() {
        $userdata = array();
        $userdata[] = $this->userID;

        $stationdata = array();
        $stationdata[] = $this->station;

        $unitdata = array();
        $unitdata[] = $this->unitID;

        if ($this->userID == 0) {//多用户同时建立
            $userdata = User::getIdArray();
        }
        //var_dump($userdata);
        if (!isset($this->station{0})) {
            $stationdata = Station::getIdArray();
        }

        if (!isset($this->unitID{0})) {
            $unitdata = Unit::getIdArray();
        }

        foreach ($userdata as $userid) {
            foreach ($stationdata as $station) {
                foreach ($unitdata as $unit) {
                    $model = ExchangeRate::model()->find('userID=:userId AND unitID=:unitID AND station=:station AND isoff=:isoff', 
                                                    array(':userId' => $userid, ':unitID' => $unit, ':station' => $station, ':isoff'=>$this->isoff));
                    if ($model === null) {
                        $model = new ExchangeRate();
                    }
                    $model->station = $station;
                    $model->pattern = $this->pattern;
                    $model->userID = $userid;
                    $model->unitID = $unit;
                    $model->isoff = $this->isoff;
                    if (!$model->save())
                        return false;
                }
            }
        }

        return true;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('pattern', $this->pattern, true);
        $criteria->compare('unitID', $this->unitID);
        $criteria->compare('station', $this->station);
        $criteria->compare('userID', $this->userID);
        $criteria->compare('isoff', $this->isoff);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 10,),
        ));
    }

}