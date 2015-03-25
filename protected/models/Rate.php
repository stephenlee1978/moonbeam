<?php

/**
 * This is the model class for table "tbl_rate".
 *
 * The followings are the available columns in table 'tbl_rate':
 * @property integer $id
 * @property integer $userId
 * @property string $station
 * @property double $rate
 * @property double $ulimit
 * @property double $llimit
 * @property double $once
 */
class Rate extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Rate the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_rate';
    }

    public static function excuteCommand($sels, $command){
        if($command == 'delAll'){
            foreach ($sels as $id) {
                Rate::deleteRate($id);
            }
        }
    }
    
    public static function deleteRate($id){
       return Yii::app()->db->createCommand()
               ->delete('tbl_rate', 'id=:id', array(':id'=>$id));
    }
    
    public static function deleteUserRate($userid){
       return Yii::app()->db->createCommand()
               ->delete('tbl_rate', 'userID=:userID', array(':userID'=>$userid));
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId', 'numerical', 'integerOnly' => true),
            array('userId', 'required', 'message' => '{attribute} 不能为空.'),
            array('rate, ulimit, llimit, once', 'numerical'),
            array('station', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, userId, station, rate, ulimit, llimit, once', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'userId' => '用户ID',
            'station' => '站点',
            'rate' => '利率',
            'ulimit' => '上限',
            'llimit' => '下限',
            'once' => '上传费用',
        );
    }

    public static function getCost($userId, $price, $numid) {
        $row = Yii::app()->db->createCommand()
                ->select('ulimit,rate,llimit')
                ->from('tbl_rate r')
               ->join('tbl_uploadhistory u', 'u.userId=r.userId AND u.num_iid=:num_iid AND r.station=u.station', 
                       array(':num_iid'=>$numid))
                ->where('r.userId=:userId', array(':userId' => $userId))
                ->queryRow();
        if ($row === false)
            return $price;

        $cost = $price*$row['rate'];
        if(isset($row['ulimit']{0})){
            $cost = $cost > $row['ulimit'] ? $row['ulimit'] : $cost;
        }
        
        if(isset($row['llimit']{0})){
            $cost = $cost < $row['llimit'] ? $row['llimit'] : $cost;
        }
        
        return $cost;
    }
    
    public static function getUploadCost($userId, $productId) {
        $row = Yii::app()->db->createCommand()
                ->select('once')
                ->from('tbl_rate r')
               ->join('tbl_product p', 'p.station=r.station AND p.id=:pid', 
                       array(':pid'=>$productId))
                ->where('r.userId=:userId', array(':userId' => $userId))
                ->queryRow();
        if ($row === false)
            return 0;
        
        return $row['once'];
    }

    public function getUserName($data, $row) {
        $user = User::Model()->findByPk($data->userId);
        if ($user === null)
            return '未知用户';
        return $user->username;
    }

    public function saveAll() {
        $userdata = array();
        $userdata[] = $this->userId;

        $stationdata = array();
        $stationdata[] = $this->station;

        if ($this->userId == 0) {//多用户同时建立
            $userdata = User::getIdArray();
        }
        //var_dump($userdata);

        if (!isset($this->station{0})) {
            $stationdata = Station::getIdArray();
        }

        foreach ($userdata as $userid) {
            foreach ($stationdata as $station) {
                $model = Rate::model()->find('userId=:userId AND station=:station', array(':userId' => $userid, ':station' => $station));
                if ($model === null) {
                    $model = new Rate();
                }
                $model->attributes = $this->attributes;
                $model->userId = $userid;
                $model->station = $station;
                if (!$model->save())
                    return false;
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

        $criteria->compare('id', $this->id);
        

        $criteria->compare('userId', $this->userId);
        $criteria->compare('station', $this->station, true);
        $criteria->compare('rate', $this->rate);
        $criteria->compare('ulimit', $this->ulimit);
        $criteria->compare('llimit', $this->llimit);
        $criteria->compare('once', $this->once);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

}