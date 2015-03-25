<?php

include_once('taobao/TbInterface.php');

class TradeController extends AuthController {

    public function actionIndex() {

        $model = new TradeManager;
        $model->unsetAttributes();
        $model->userID = Yii::app()->user->id;
        
        if (isset($_POST['TradeManager'])) {
            $model->attributes = $_POST['TradeManager'];
            $model->validate();
        }

        $this->render('index', array(
            'model' => $model,
        ));

    }

    // Uncomment the following methods and override them if needed
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function actionAdmin() {
        $model = new TradeManager;
        $model->unsetAttributes();

        if (isset($_POST['TradeManager'])) {
            $model->attributes = $_POST['TradeManager'];
            $model->validate();
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionExport($cmd) {
        if (!Yii::app()->request->isPostRequest)
            Yii::app()->end();

        if (!isset($_POST['TradeManager']))
            Yii::app()->end;
        $model = new TradeManager;
        $model->unsetAttributes();
        $model->attributes = $_POST['TradeManager'];
        
        $bRet = false;
        if($model->validate()){
            
            if ($cmd == 'uploads') {
                $bRet = $this->exportUploads($model);
            } else {
                $bRet = $this->exportStatistics($model);
            }
        }
        if ($bRet === true)
            echo CJSON::encode(array('success' => true));
        else {
            echo CJSON::encode(array('success' => false));
        }
    }

    private function exportUploads($model) {
        $readrows = $model->getUploadsAraay();
        if ($readrows === false)
            return false;

        $fp = fopen('php://temp', 'w');

        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, array('货号', '淘宝商品编号', '站点', '商品URL', '收费'));

        $totalcost ='0';
        foreach ($readrows as $readrow) {
            $row = array();
            $row[] = $readrow['productId'];
            $row[] = $readrow['num_iid'];
            $row[] = $readrow['station'];
            $row[] = $readrow['url'];
            $row[] = $readrow['cost'];
            $totalcost = $readrow['totalcost'];
            fputcsv($fp, $row);
            unset($row);
        }
        fputcsv($fp, array('', '', '', '总收费', $totalcost));
        rewind($fp);
        Yii::app()->user->setState('export', stream_get_contents($fp));
        fclose($fp);

        return true;
    }

    private function exportStatistics($model) {
        $readrows = $model->getStatisticsAraay();
        if ($readrows === false)
            return false;

        $fp = fopen('php://temp', 'w');

        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, array('支付单号', '交易时间', '订单时间', '成交金额', '商品', '买家', '收费'));
        
        $totalcost ='0';
        foreach ($readrows as $readrow) {

            $row = array();
            $row[] = $readrow['tid'];
            $row[] = $readrow['tradetime'];
            $row[] = $readrow['paytime'];
            $row[] = $readrow['payment'];
            $row[] = $readrow['title'];
            $row[] = $readrow['buyer_nick'];
            $row[] = $readrow['cost'];
            $totalcost = $readrow['totalcost'];
            fputcsv($fp, $row);
            unset($row);
        }
        fputcsv($fp, array('', '', '', '', '', '总收费', $totalcost));
        rewind($fp);
        Yii::app()->user->setState('export', stream_get_contents($fp));
        fclose($fp);

        return true;
    }

    //导出excel表
    public function actionExportFile() {
        Yii::app()->request->sendFile('excel.csv', Yii::app()->user->getState('export'));
        Yii::app()->user->clearState('export');
    }

}