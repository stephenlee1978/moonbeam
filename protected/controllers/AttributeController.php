<?php
/*
 * 2015/03/30 stephenlee
 */
class AttributeController extends AuthController {

    public function actionIndex() {
        $model = new Attribute('search');
        $model->unsetAttributes();
        $model->userID = Yii::app()->user->id;
        if (isset($_GET['Attribute']))
            $model->attributes = $_GET['Attribute'];
        
        $this->render('index', array(
            'model' => $model,
        ));
    }
    
    public function actionAdmin() {
        $model = new Attribute('search');
        $model->unsetAttributes();
        
        if (isset($_GET['Attribute']))
            $model->attributes = $_GET['Attribute'];
        
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    private function returnUrl(){
       if(Yii::app()->user->isAdministrator()){
           $this->redirect(array('/attribute/admin'));
       }else{
           $this->redirect(array('/attribute/index'));
       }
   }
   
    public function actionCreate() {
        $model = new Attribute;
        $model->unsetAttributes();
        $model->userID = Yii::app()->user->id;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Attribute'])) {
            $model->attributes = $_POST['Attribute'];
            if ($model->validate() &&$model->saveAll()) {
                Yii::app()->user->setFlash('attribute_msg', '创建属性设置成功');      
                $this->returnUrl();
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    public function actionCommand(){
        if(!Yii::app()->request->isPostRequest) Yii::app ()->end();
        
        if(isset($_POST['selid'])){
            Attribute::excuteCommand($_POST['selid'], $_POST['cmd']);
            echo CJSON::encode(array('success'=>true));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate() {
        if(!Yii::app()->request->isPostRequest) Yii::app ()->end ();
        
        if(isset($_POST['pk'])){
            $modle = $this->loadModel($_POST['pk']);
            if($modle!==null){
                $modle->setAttribute($_POST['name'], $_POST['value']);
                if(!$modle->setAttribute($_POST['name'], $_POST['value']) || !$modle->save()){
                     throw new Exception('设置错误:');
                }
            }
        }
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
    
    public function loadModel($id) {
        $model = Attribute::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}