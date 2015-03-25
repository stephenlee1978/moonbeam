<?php

class ExpressController extends AuthController {

    public function actionIndex() {
        $model = new Express('search');
        $model->unsetAttributes();  // clear any default values
        $model->userID = Yii::app()->user->id;
        
        if (isset($_GET['Express']))
            $model->attributes = $_GET['Express'];

        $this->render('index', array(
            'model' => $model,
        ));
    }
    
    public function actionAdmin() {
        $model = new Express('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Express']))
            $model->attributes = $_GET['Express'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }
    
   private function returnUrl(){
       if(Yii::app()->user->isAdministrator()){
           $this->redirect(array('/express/admin'));
       }else{
           $this->redirect(array('/express/index'));
       }
   }
   
    public function actionCreate() {
        $model = new Express;
        if(Yii::app()->user->isAdministrator()){
            $model->userID = Yii::app()->user->id;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Express'])) {
            $model->attributes = $_POST['Express'];
            if ($model->validate() && $model->saveAll()) {
                Yii::app()->user->setFlash('express_msg', '创建运费成功');      
                $this->returnUrl($bUser);
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
            Express::excuteCommand($_POST['selid'], $_POST['cmd']);
            echo CJSON::encode(array('success'=>true));
        }
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Express'])) {
            $model->attributes = $_POST['Express'];
            if ($model->validate() && $model->saveAll()) {
                Yii::app()->user->setFlash('express_msg', '修改运费成功');      
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
        $model = Express::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}