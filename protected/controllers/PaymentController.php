<?php

class PaymentController extends AuthController {

    public function actionIndex() {
        $model = new Payment;
        $model->unsetAttributes();
        
        $this->render('index', array('model'=>$model));
    }
    
    public function actionAdmin() {
        $model = new Payment;
        $model->unsetAttributes();
        
        $this->render('index', array('model'=>$model));
    }
}