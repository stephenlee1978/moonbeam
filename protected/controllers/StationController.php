<?php

class StationController extends AuthController {
     public $defaultAction = 'admin';
     
    public function actionAdmin() {
        $model = new Station();
        $model->unsetAttributes();
        
        if (isset($_GET['Station']))
            $model->attributes = $_GET['Station'];
        
        $this->render('admin', array('model'=>$model));
    }
    
    public function actionCreate() {
        $model = new Station;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Station'])) {
            $model->attributes = $_POST['Station'];
            if ($model->save()) {
                Yii::app()->user->setFlash('station_msg', '创建站点成功');      
                $this->redirect(array('admin'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Station'])) {
            $model->attributes = $_POST['Station'];
            if ($model->save()) {
                Yii::app()->user->setFlash('station_msg', '修改站点成功');      
                $this->redirect(array('admin'));
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    public function loadModel($id) {
        $model = Station::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}