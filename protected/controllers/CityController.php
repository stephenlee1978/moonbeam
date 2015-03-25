<?php

class CityController extends AuthController {

    public function actionIndex() {
        $model = new City;
        $model->unsetAttributes();
        
        if (isset($_GET['City']))
            $model->attributes = $_GET['City'];
        
        $this->render('index', array('model'=>$model));
        
    }
    
    public function actionCreate() {
        $model = new City;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['City'])) {
            $model->attributes = $_POST['City'];
            if ($model->save()) {
                Yii::app()->user->setFlash('city_msg', '创建城市成功');      
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    public function actionUpdate() {
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    public function loadModel($id) {
        $model = City::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}