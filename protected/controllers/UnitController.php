<?php

class UnitController extends AuthController {

    public function actionIndex() {
        $model = new Unit;
        $model->unsetAttributes();
        
        if (isset($_GET['Unit']))
            $model->attributes = $_GET['Unit'];
        
        $this->render('index', array('model'=>$model));
    }

    public function actionCreate() {
        $model = new Unit;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Unit'])) {
            $model->attributes = $_POST['Unit'];
            if ($model->save()) {
                Yii::app()->user->setFlash('unit_msg', '创建货币成功');      
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    public function actionUpdate($id) {
        if(!Yii::app()->request->isAjaxRequest) Yii::app ()->end ();
        
        Yii::import('application.lib.Functions');
            $modle = $this->loadModel($id);
            if($modle!==null){
                $data = Functions::getExchangeRate($modle->unit, 'CNY');
                if($data === false){
                    echo CJSON::encode(array('success'=>false));
                }else{
                    $modle->setAttribute('rate', $data);
                    $modle->save();
                    echo CJSON::encode(array('success'=>true));
                }
                
            } 
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
    
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }
    
    public function loadModel($id) {
        $model = Unit::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}