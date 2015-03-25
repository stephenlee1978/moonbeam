<?php

class TaobaoController extends AuthController {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    //下架
    public function actionInstock($id) {
        if ($this->setInstock($id)) {
            echo CJSON::encode(true);
        } else {
            echo CJSON::encode(false);
        }
    }

    private function setInstock($id) {
        $num_iid = Uploadhistory::fideNumIid($id);
        if ($num_iid !== false) {
            Yii::import('lib.Taobao');
            if (Taobao::itemUpdateDelisting($num_iid)) {
                ProductProperty::updateApproveStatus('instock', $id);
                return true;
            }
        }
        return false;
    }

    //批量下架淘宝商品
    public function actionInstockAll() {
        if (isset($_POST['selectdel'])) {
            $selArry = $_POST['selectdel'];
            foreach ($selArry as $id) {
                $this->setInstock($id);
            }
        }
        echo CJSON::encode(array('success' => true));
    }

    //批量删除淘宝商品
    public function actionDeleteAll() {
        Yii::import('lib.Taobao');

        if (isset($_POST['selectdel'])) {
            $selArry = $_POST['selectdel'];
            foreach ($selArry as $id) {
                $this->deleteTaobaoItem($id);
            }
        }
        echo CJSON::encode(array('success' => true));
    }

    private function deleteTaobaoItem($id) {
        $num_iid = Uploadhistory::fideNumIid($id);
        if ($num_iid !== false) {
            if (Taobao::deleteItem($num_iid)) {
                Uploadhistory::deleteByPid($id);
                return true;
            }
        }
        return false;
    }

    //删除淘宝商品
    public function actionDelete($id) {
        Yii::import('lib.Taobao');
        if ($this->deleteTaobaoItem($id)) {
            echo CJSON::encode(true);
        }


        echo CJSON::encode(false);
    }

    //批量采集
    public function actionCollect($id = 0) {
        Yii::import('application.lib.productCollect.CollectClass');
        $collect = new CollectClass;
        $ids = array();
        if ($id != 0 || $id != '0') {
            $ids[] = $id;
        } elseif (isset($_POST['selid'])) {
            $ids = $_POST['selid'];
        }
        $collect->batchExcute($ids);
        unset($collect);
        unset($ids);
    }

    //获取用户进三个月交易
    public function actionTrade() {
        if (isset($_POST['TradeManager'])) {
            $model = new TradeManager();
            $model->attributes = $_POST['TradeManager'];
            $model->getTaobaoTradeData();
        }
    }

    //编辑商品淘宝属性
    public function actionEdit($id = 0) {
        $form = null;
        if ($id != 0 || $id != '0') {
            $ids[] = $id;
            $form = ProductEditForm::createEditForm($ids);
        } elseif (isset($_POST['selid'])) {
            $form = ProductEditForm::createEditForm($_POST['selid']);
        }

        if ($form === null){
            throw new CHttpException(304, '编辑商品异常!');
        }

        if (isset($_POST['ProductProperty'])) {
            $form->property->unsetAttributes();
            $form->property->freight = 0;
            $form->property->attributes = $_POST['ProductProperty'];
        }else{
            if(!isset($form->property->freight)){
                $form->property->freight = 0;
            }
        }
        $form->models[0]->freight = $form->property->freight;


        if (isset($_POST['method'])) {
            $form->scenario = $_POST['method'];
            if ($_POST['method'] === 'upload') {
                if ($form->validate() && $form->save()) {
                    $this->layout = 'trade';
                    $this->render('upload', array('ids' => $form->ids));
                    Yii::app()->end();
                }
            }
            $form->load();
        }

        $this->render('edit', array(
            'id' => $id,
            'edit' => $form,
            'method' => $form->scenario,
            'models' => $form->models,
            'property' => $form->property,
            'itemProps' => $form->itemProps
        ));
    }

    //上传商品
    public function actionUploadProduct() {
        echo('actionUploadProduct');
        if (isset($_GET['ids'])) {
            foreach ($_GET['ids'] as $id) {
                echo('发现ids:' . $id);
            }
        }
    }

    /**
     * 获取卖家标准类目.
     * 
     */
    public function actionSellercats() {
        Yii::import('lib.Taobao');

        try {
            $resp = Taobao::getSellercatsList();

            if (isset($resp->seller_cats))
                echo CJSON::encode($resp->seller_cats->seller_cat);
            else
                print_r($resp);
        } catch (Exception $e) {
            throw new CException('actionSellercats exception! error:' . $e->getMessage());
        }
    }

    //得到标准商品属性
    public function actionItemProps() {
        Yii::import('lib.Taobao');
        if (isset($_GET['cid']) && $_GET['child_path']) {
            try {
                $resp = Taobao::getItemProps($_GET['cid'], $_GET['child_path']);
                echo CJSON::encode($resp);
            } catch (Exception $e) {
                
            }
        }
    }

    //获取淘宝后台标准类目
    public function actionItemCats() {
        Yii::import('lib.Taobao');

        if (isset($_GET['pid'])) {
            try {
                $resp = Taobao::getItemcats($_GET['pid']);

                if (isset($resp->item_cats)) {
                    if (count($resp))
                        echo CJSON::encode($resp->item_cats->item_cat);
                }
                else {
                    Yii::log('actionItemCats error code=' . $resp->code);
                }
            } catch (Exception $e) {
                
            }
        }
    }

    //单个上传
    public function actionItemAdd($id) {
        Yii::import('lib.taobao.TbInterface');
        $tbInterface = new TbInterface;
        $tbInterface->uploadProduct($id);
    }

    //采集页面
    public function actionIndex() {
        $model = new CollectForm;
        $model->unsetAttributes();

        if (isset($_POST['CollectForm'])) {
            $model->attributes = $_POST['CollectForm'];
            if ($model->validate()) {
                $model->excute();
            } else {
                var_dump($model->errors);
            }
            Yii::app()->end();
        }

        $this->render('index', array('model' => $model));
    }

    public function actionLinks() {
        Yii::app()->user->setCurMenu('task');

        $model = new ProductLink;
        $this->render('links', array('model' => $model));
    }

    /**
     * 重新同步
     * 
     */
    public function actionSyn() {
        Yii::app()->user->setCurMenu('task');

        if (isset($_POST['url'])) {

            $url = $_POST['url'];
            $type = $_POST['p_type'];

            $station = Station::getStationClass($url);
            if (strlen($station) == 0) {
                echo '未找到相应网站抓起!';
                Yii::app()->end();
            }
            $obj = new $station;
            $obj->OUTMESSAGE = true;
            $obj->collectUrl($url, $type);
            unset($station);
        }
    }

    /**
     * 管理任务
     */
    public function actionAdmin() {

        $this->render('admin');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Task the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Task::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Task $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'collect_form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
