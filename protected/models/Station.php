<?php

/**
 * This is the model class for table "tbl_station".
 *
 * The followings are the available columns in table 'tbl_station':
 * @property string $station
 * @property string $captureClass
 */
class Station extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Station the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_station';
    }

    public static function getIdArray() {
        $ids = array();

        $rowdata = Yii::app()->db->createCommand()
                ->from('tbl_station')
                ->select('station')
                ->queryAll();
        foreach ($rowdata as $row) {
            $ids[] = $row['station'];
        }
        return $ids;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('station, captureClass, addr', 'required'),
            array('station, captureClass', 'length', 'max' => 20),
            array('addr', 'length', 'max' => 50),
            array('addr, station, captureClass', 'safe', 'on' => 'search'),
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
            'station' => '站点标示',
            'captureClass' => '采集类',
            'addr' => '地址',
        );
    }

    public static function getStationClass($url) {
        $rowdata = Yii::app()->db->createCommand()
                ->select('station, captureClass')
                ->from('tbl_station')
                ->queryAll();
        foreach ($rowdata as $row) {
            if (stripos($url, $row['station']) !== false) {
                return $row['captureClass'];
            }
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
        $criteria->compare('addr', $this->addr, true);
        $criteria->compare('station', $this->station, true);
        $criteria->compare('captureClass', $this->captureClass, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

}