<?php

/**
 * This is the model class for table "tbl_statistics".
 *
 * The followings are the available columns in table 'tbl_statistics':
 * @property string $id
 * @property integer $userId
 * @property string $tradetime
 * @property string $num_iid
 * @property double $payment
 * @property string $title
 * @property string $buyer_nick
 * @property string $productId
 */
class Statistics extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Statistics the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getStatistics($id, $tid, $oid) {
        $row = Yii::app()->db->createCommand()
                ->select('tid')
                ->from('tbl_statistics s')
                ->where('userId=:userId AND tid=:tid AND oid=:oid', array(':userId'=>$id, ':tid'=>$tid, 'oid'=>$oid))
                ->queryRow();
        
        if($row === false) {
            return new Statistics;
        }
        return false;
    }

    protected function beforeSave(){
        
        $this->cost = Rate::getCost($this->userId, $this->payment, $this->num_iid);
        
        return parent::beforeSave();
    }
    
    public static function saveStatistics($id, $trade) {

        $pay_time = $trade->created;
        $receiver_name = $trade->receiver_name;
        $receiver_state = $trade->receiver_state;
        $receiver_address = $trade->receiver_address;
        $receiver_city = $trade->receiver_city;
        $buyer_nick = $trade->buyer_nick;
        $tid = sprintf('%.0f', $trade->tid);
        if(isset($trade->orders->order[0])) $trade->orders->order[0]->payment += $trade->post_fee;
        foreach ($trade->orders->order as $key=>$order) {
            if(Uploadhistory::isUploaded($order->num_iid, $id) === false) continue;
            
            $statistics = Statistics::getStatistics($id, $tid, $order->oid);
            if($statistics === false) continue;

            $statistics->tradetime = $pay_time;
            $statistics->receiver = $receiver_name.'-'.$receiver_state.'-'.$receiver_city.'-'.$receiver_address;
            $statistics->userId= $id;
            $statistics->tid = $tid;
            $statistics->oid = sprintf('%.0f', $order->oid);
            $statistics->title = $order->title;
            if(isset($order->pic_path))
                $statistics->pic_path = $order->pic_path;
            $statistics->buyer_nick = $buyer_nick;
            $statistics->payment = $order->payment;
            $statistics->num = $order->num;
            $statistics->paytime = $order->end_time;
            $statistics->num_iid = $order->num_iid;
            $statistics->save();
            unset($statistics);
        }
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_statistics';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('userId,num', 'numerical', 'integerOnly' => true),
            array('payment', 'numerical'),
            array('title', 'length', 'max' => 60),
            array('num_iid, buyer_nick', 'length', 'max' => 50),
            array('tradetime,paytime,oid, tid,pic_path,receiver', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('tid, oid, userId, tradetime, paytime, num_iid, payment, title, buyer_nick, cost', 'safe', 'on' => 'search'),
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
            'oid'=>'oid',
            'pic_path'=>'图片',
            'num'=>'交易数据',
            'receiver'=>'收货地址',
            'tid' => '订单编号',
            'userId' => 'User',
            'tradetime' => '成交时间',
            'paytime' => '完成时间',
            'num_iid' => 'Num Iid',
            'payment' => '成交金额',
            'title' => '商品',
            'buyer_nick' => '买家',
            'cost' => '回扣金额',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = array();

        if (isset($this->paytime{1})) {
            $arryTime = explode("-", $this->paytime);
            $year = intval($arryTime[0]);
            $month = intval($arryTime[1]);
            $criteria = Yii::app()->db->createCommand("select * from tbl_statistics WHERE YEAR(paytime) = $year AND MONTH(paytime) = $month AND userId = $this->userId;")->queryAll();
        }

        return new CArrayDataProvider($criteria, array(
            'id' => 'report',
        ));
    }

}