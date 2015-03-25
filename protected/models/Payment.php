<?php

/**
 * This is the model class for table "tbl_payment".
 *
 * The followings are the available columns in table 'tbl_payment':
 * @property integer $id
 * @property integer $userID
 * @property string $money
 * @property string $createTime
 * @property integer $state
 * @property string $finishTime
 */
class Payment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Payment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userID, state', 'numerical', 'integerOnly'=>true),
			array('money', 'length', 'max'=>10),
			array('createTime, finishTime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userID, money, createTime, state, finishTime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userID' => 'User',
			'money' => 'Money',
			'createTime' => 'Create Time',
			'state' => 'State',
			'finishTime' => 'Finish Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('finishTime',$this->finishTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}