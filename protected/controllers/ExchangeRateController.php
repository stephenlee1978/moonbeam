<?php
class ExchangeRateController extends AuthController {

    public function actionIndex() {
        $model = new ExchangeRate('search');
        $model->unsetAttributes();  // clear any default values
        $model->userID = Yii::app()->user->id;
        if (isset($_GET['ExchangeRate']))
            $model->attributes = $_GET['ExchangeRate'];

        $this->render('index', array(
            'model' => $model,
        ));
    }
   
   //复制用户配置
   public function actionCopyconfig() {
       if(isset($_POST['fuserID']) && isset($_POST['tuserID'])){
           ExchangeRate::copyUserConfig($_POST['fuserID'], $_POST['tuserID']);
           Yii::app()->user->setFlash('erate_msg', '复制用户配置成功');      
       }
       $this->redirect(array('/exchangeRate/admin'));
   }
   
    public function actionAdmin() {
        $model = new ExchangeRate('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ExchangeRate']))
            $model->attributes = $_GET['ExchangeRate'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }
    
    public function actionCreate() {
        $model = new ExchangeRate;
        if(Yii::app()->user->isAdministrator())
            $model->userID = Yii::app()->user->id;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ExchangeRate'])) {
            $model->attributes = $_POST['ExchangeRate'];
            if ($model->validate() && $model->saveAll()) {
                Yii::app()->user->setFlash('erate_msg', '创建汇率成功');      
                $this->returnUrl();
            }else{
                Yii::app()->user->setFlash('erate_msg', '创建汇率失败');  
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    public function actionAjaxUpdate() {
        if(!Yii::app()->request->isPostRequest) Yii::app ()->end ();

        if(isset($_POST['pk'])){
            $modle = $this->loadModel($_POST['pk']);
            if($modle!==null){
                $modle->setAttribute($_POST['name'], $_POST['value']);
                $modle->save();
            }
        }
    }
    
    public function actionCommand(){
        if(!Yii::app()->request->isPostRequest) Yii::app ()->end();
        
        if(isset($_POST['selid'])){
            ExchangeRate::excuteCommand($_POST['selid'], $_POST['cmd']);
            echo CJSON::encode(array('success'=>true));
        }
    }
    
    private function returnUrl(){
       if(Yii::app()->user->isAdministrator()){
           $this->redirect(array('/exchangeRate/admin'));
       }else{
           $this->redirect(array('/exchangeRate/index'));
       }
   }
   
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ExchangeRate'])) {
            $model->attributes = $_POST['ExchangeRate'];
            if ($model->validate() && $model->saveAll()) {
                Yii::app()->user->setFlash('erate_msg', '修改汇率成功');      
                $this->returnUrl();
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }
    
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }
    
    public function loadModel($id) {
        $model = ExchangeRate::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}