<?php

class UserController extends AuthController {

    public $defaultAction = 'admin';
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    //用户信息
    public function actionInfo() {
        $this->render('info', array(
            'model' => $this->loadModel(Yii::app()->user->id),
        ));
    }

    public function actionModifyPassword() {
        if (isset($_POST['password'])) {
            if (User::modifyPassword($_POST['password']))
                echo CJSON::encode(array('success' => true));
            Yii::app()->end();
        }
        echo CJSON::encode(array('success' => false));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionAlert($name) {
        $this->render('alert', array(
            'name' => $name,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new User;

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()){
                TblAssignments::saveUser($model->id, $model->level);
                Yii::app()->user->setFlash('user_msg', '创建新用户成功');      
            }
            $this->redirect(array('user/admin'));
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

        if (isset($_POST['User'])) {
            $transaction = $model->dbConnection->beginTransaction();
            try {
                $model->modifyPassWord = $model->password;
                $model->attributes = $_POST['User'];
                if ($model->save()) {
                    $transaction->commit();
                    TblAssignments::saveUser($model->id, $model->level);
                    Yii::app()->user->setFlash('user_msg', '更新用户成功');
                    $this->redirect(array('user/admin'));
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
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
        
        if($this->loadModel($id)->delete()){
            Attribute::deleteUserAttribute($id);
            ExchangeRate::deleteUserExchangeRate($id);
            Express::deleteUserExpress($id);
            Rate::deleteUserRate($id);
            Uploadhistory::deleteByUserId($id);
            
            TblAssignments::deleteUser($id);
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::findUserByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
