<?php

/**
 * This is the model class for table "tbl_unit".
 *
 * The followings are the available columns in table 'tbl_unit':
 * @property integer $id
 * @property string $unit
 */
class Unit extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Unit the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_unit';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sign, id, remark', 'required'),
            array('sign,id', 'length', 'max' => 20),
            array('rate,rateTime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('sign, rate, rateTime, id', 'safe', 'on' => 'search'),
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

    public static function getIdArray() {
        $ids = array();

        $rowdata = Yii::app()->db->createCommand()
                ->from('tbl_unit')
                ->select('id')
                ->queryAll();
        foreach ($rowdata as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }
    
    public static function getSignByPk($pk) {
        
        $row = Yii::app()->db->createCommand()
                ->from('tbl_unit')
                ->select('sign')
                ->where('id=:id', array('id'=>$pk))
                ->queryRow();
        if (isset($row['sign'])) {
            return $row['sign'];
        }
        return '';
    }
    
    public static function getRemarkByPk($pk) {
        
        $row = Yii::app()->db->createCommand()
                ->from('tbl_unit')
                ->select('remark')
                ->where('id=:id', array('id'=>$pk))
                ->queryRow();
        if (isset($row['remark'])) {
            return $row['remark'];
        }
        return '未知';
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'sign' => '货币符号',
            'id' => '货币标准',
            'remark'=>'货币描述',
            'rate'=>'当前计算汇率',
            'rateTime'=>'汇率更新时间',
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

        $criteria->compare('sign', $this->sign);
        $criteria->compare('id', $this->id, true);
        $criteria->compare('remark', $this->remark, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}