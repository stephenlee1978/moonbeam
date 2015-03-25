<?php

class RateController extends AuthController {
    /**
     * @return array action filters
     */
    public $defaultAction = 'admin';
    
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    
    public function actionCommand(){
        if(!Yii::app()->request->isPostRequest) Yii::app ()->end();
        
        if(isset($_POST['selid'])){
            Rate::excuteCommand($_POST['selid'], $_POST['cmd']);
            echo CJSON::encode(array('success'=>true));
            
        }  else {
            echo CJSON::encode(array('success'=>false));
        }
        
    }
    
    /**
     * 显示用户收费列表
     */
    public function actionIndex() {

        $model = new Rate('search');
        $model->unsetAttributes();  // clear any default values
        $model->userId = Yii::app()->user->id;
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Rate;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Rate'])) {
            $model->attributes = $_POST['Rate'];
            if ($model->validate() && $model->saveAll()) {
                Yii::app()->user->setFlash('rate_msg', '创建收费成功');      
                $this->redirect(array('rate/admin'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Rate'])) {
            $model->attributes = $_POST['Rate'];
            if ($model->validate() && $model->saveAll()) {
                Yii::app()->user->setFlash('rate_msg', '修改收费成功');      
                $this->redirect(array('rate/admin'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {

        $model = new Rate('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Rate']))
            $model->attributes = $_GET['Rate'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Rate the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Rate::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Rate $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'rate-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
