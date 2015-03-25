<?php

class FileController extends AuthController {

    public function actionIndex() {
        Yii::app()->end();
    }

    //删除图片
    public function actionDelete(){
        if(isset($_POST['file'])){
            if(File::deleteImages($_POST['file']))
                echo CJSON::encode (array('sucess'=>true));
        }
    }
    
}