<?php

/**
 * This is the model class for table "assignments".
 *
 * The followings are the available columns in table 'assignments':
 * @property string $itemname
 * @property string $userid
 * @property string $bizrule
 * @property string $data
 */
class TblAssignments extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'assignments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('itemname, userid', 'length', 'max' => 64),
            array('bizrule, data', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('itemname, userid, bizrule, data', 'safe', 'on' => 'search'),
        );
    }
    
    /**
     * 保存用户进等级表
     */
    public static function saveUser($userId, $level){
        $model = TblAssignments::model()->find('userid=:userid', array(':userid'=>$userId));
        if($model===null){
            $model = new TblAssignments();
        }
        $model->userid = $userId;
        $model->data = 's:0:"";';
        switch ($level) {
            case 2:
                 $model->itemname = "Authority";
                break;
            case 1:
                 $model->itemname = "WebUser";
                break;
            default:
                $model->itemname = "Taobao";
                break;
        }
        $model->save();
    }
    
    /**
     * 删除用户进等级表
     */
    public static function deleteUser($userId){
        Yii::app()->db->createCommand()
                ->delete('assignments',  'userid=:userID', array(':userID'=>$userId));
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
            'itemname' => 'Itemname',
            'userid' => 'Userid',
            'bizrule' => 'Bizrule',
            'data' => 'Data',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('itemname', $this->itemname, true);
        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('bizrule', $this->bizrule, true);
        $criteria->compare('data', $this->data, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TblAssignments the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
