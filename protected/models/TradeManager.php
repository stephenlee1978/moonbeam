<?php

/**
 * Description of TradeManager
 *
 * @author Administrator
 */
class TradeManager extends CFormModel {

    public $userID;
    public $beginTime;
    public $endTime;
    public $month;

    public function rules() {
        return array(
            array('userID, beginTime, endTime', 'required', 'message' => '{attribute} 不能为空.'),
            array('beginTime, endTime, month', 'safe'),
        );
    }

    public function attributeLabels() {
        return array(
            'userID' => '用户ID',
            'beginTime' => '交易开始时间',
            'endTime' => '交易结束时间',
            'month' => '查询月份',
        );
    }

    public function validate($attributes = NULL, $clearErrors = true) {
        if (isset($this->month{0})) {
            $timestamp = strtotime($this->month);
            $mdays = date('t', $timestamp);
            $this->beginTime = date('Y-m-1 00:00:00', $timestamp);
            $this->endTime = date('Y-m-' . $mdays . ' 23:59:59', $timestamp);
        }
        parent::validate($attributes, $clearErrors);
    }

    //搜索Statistics
    public function searchStatistics() {
        $criteria = new CDbCriteria;

        if (isset($this->beginTime{0}) && isset($this->endTime{0})) {
            if (!isset($this->userID))
                $criteria->compare('userId', 0);
            else {
                $criteria->compare('userId', $this->userID, true);
            }


            $criteria->addCondition("paytime>=:beginTime");
            $criteria->params[':beginTime'] = $this->beginTime;


            $criteria->addCondition("paytime<=:endTime");
            $criteria->params[':endTime'] = $this->endTime;

            $criteria->order = 'paytime DESC';
        } else {
            $criteria->compare('userId', 0);
        }



        return new CActiveDataProvider('Statistics', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    public function getUserName($data, $row) {
        $user = User::Model()->findByPk($data->userId);
        return $user->username;
    }

    public function getPiture($data, $row) {
        if (isset($data->pic_path{0})) {
            return CHtml::image($data->pic_path, '', array('width' => "50px", 'height' => "50px"));
        }
        return CHtml::image(Yii::app()->baseUrl . '/img/loading.gif', '', array('width' => "50px", 'height' => "50px"));
    }

    public function getProductUrl($data, $row) {
        return CHtml::link('官网地址', $data->url, array('class' => 'btn'));
    }

    public function getPieData() {
        if (!isset($this->userID))
            return array();

        $statistics = $this->searchStatistics()->getItemCount();
        $uploads = $this->searchUploads()->getItemCount();

        if ($statistics == 0)
            return array();
        $percen = ((float) $statistics / $uploads) * 100;

        return array(
            array('成交量', $percen),
            array('上传量', 100 - $percen)
        );
    }

    public function getStatisticsCost() {
        if (!isset($this->userID))
            return '';

        $command = Yii::app()->db->createCommand();
        $command->select('sum(cost) as totalcost')
                ->from('tbl_statistics')
                ->where('userId=:userid', array(':userid' => $this->userID));


        $command->andWhere('paytime >= :TimeStart', array(':TimeStart' => $this->beginTime));



        $command->andWhere('paytime <= :TimeEnd', array(':TimeEnd' => $this->endTime));


        $row = $command->queryRow();

        if (!isset($row['totalcost']))
            return '';

        return $row['totalcost'];
    }

    public function getUploadsCost() {
        if (!isset($this->userID))
            return '';

        $command = Yii::app()->db->createCommand();
        $command->select('sum(cost) as totalcost')
                ->from('tbl_uploadhistory')
                ->where('userId=:userid', array(':userid' => $this->userID));

        $command->andWhere('uploadTime >= :TimeStart', array(':TimeStart' => $this->beginTime));

        $command->andWhere('uploadTime <= :TimeEnd', array(':TimeEnd' => $this->endTime));


        $row = $command->queryRow();

        if (!isset($row['totalcost']))
            return '';

        return $row['totalcost'];
    }

    public function getUploadsAraay() {
        if (!isset($this->userID) || !isset($this->beginTime) && !isset($this->endTime))
            return false;

        $command = Yii::app()->db->createCommand();
        $command->select('sum(cost) as totalcost, *')
                ->from('tbl_uploadhistory')
                ->where('userId=:userid', array(':userid' => $this->userID));

        $command->andWhere('uploadTime >= :TimeStart', array(':TimeStart' => $this->beginTime));

        $command->andWhere('uploadTime <= :TimeEnd', array(':TimeEnd' => $this->endTime));


        return $command->queryAll();
    }

    public function getStatisticsAraay() {
        if (!isset($this->userID) || !isset($this->beginTime) && !isset($this->endTime))
            return false;

        $command = Yii::app()->db->createCommand();
        $command->select('sum(cost) as totalcost,*')
                ->from('tbl_statistics')
                ->where('userId=:userid', array(':userid' => $this->userID));

        $command->andWhere('paytime >= :TimeStart', array(':TimeStart' => $this->beginTime));

        $command->andWhere('paytime <= :TimeEnd', array(':TimeEnd' => $this->endTime));


        return $command->queryAll();
    }

    public function getStatisticsCount() {
        if (!isset($this->userID))
            return 0;
        return $this->searchStatistics()->getItemCount();
    }

    public function getUploadsCount() {
        if (!isset($this->userID))
            return 0;
        return $this->searchUploads()->getItemCount();
    }

    //搜索Uploads
    public function searchUploads() {

        $criteria = new CDbCriteria;

        if (isset($this->beginTime{0}) && isset($this->endTime{0})) {
            if (!isset($this->userID))
                $criteria->compare('userId', 0);
            else {
                $criteria->compare('userId', $this->userID, true);
            }


            $criteria->addCondition("uploadTime>=:beginTime");
            $criteria->params[':beginTime'] = $this->beginTime;



            $criteria->addCondition("uploadTime<=:endTime");
            $criteria->params[':endTime'] = $this->endTime;
            
            $criteria->order = 'uploadTime DESC';
        }else{
            $criteria->compare('userId', 0);
        }

        

        return new CActiveDataProvider('Uploadhistory', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        
    }

    //得到淘宝交易数据处理
    public function getTaobaoTradeData() {
        Yii::import('lib.Taobao');
        $resp = Taobao::getTradesSold($this->userID, $this->beginTime, $this->endTime);
        if ($resp === false)
            return;

        foreach ($resp->trades->trade as $trade) {
            try {
                Statistics::saveStatistics($this->userID, $trade);
            } catch (Exception $e) {
                
            }
        }

        if (isset($resp->has_next) && $resp->has_next)
            $this->getTaobaoTradeData();
    }

}

?>
