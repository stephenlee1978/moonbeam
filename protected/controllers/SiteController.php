<?php

class SiteController extends CController {

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        if(Yii::app()->user->isGuest){
           $this->redirect(array('/site/login'));
       }
       $this->redirect(array('/product/index'));
    }

    public function actionToken() {
        if (!isset($_GET['code'])) {
            throw new CHttpException(1978, '授权失败，获取code失败，请重试');
        }

        Yii::import('application.lib.http.Auth');
        $token_endpoint = Yii::app()->createAbsoluteUrl('/site/token');
        $auth = new Auth();
        $resp = $auth->getToken(Yii::app()->params['token_url'], $_GET['code'], Yii::app()->params['AppKey'], Yii::app()->params['AppSecret'], $token_endpoint);
        if ($resp === false) {
            throw new CHttpException(3, '获取淘宝Token失败!');
            Yii::app()->end();
        }

        $ret = Yii::app()->user->authorization($resp);
        if ($ret === false) {
            throw new CHttpException(4, '用户授权失败!');
            Yii::app()->end();
        }
        $this->redirect(array('/product/index'));
    }

    //已有淘宝用户登录
    public function actionTaobaoLogin() {
        Yii::import('application.lib.http.Auth');

        $authorize_endpoint = Yii::app()->createAbsoluteUrl('/site/token');
        $auth = new Auth();
        try {
            $auth->getCode(Yii::app()->params['authorize_url'], Yii::app()->params['AppKey'], $authorize_endpoint);
        } catch (Exception $ex) {
            Yii::app()->end();
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            $this->render('error', array('error' => $error));
        }
    }

    /**
     * Displays the contact page
     */
    public function actionAbout() {
        $this->render('about');
    }

    /**
     * 正常用户登录页面
     */
    public function actionLogin() {
        $this->layout = '//layouts/site';
        
        $model = new LoginForm;

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];

            if ($model->validate() && $model->login()) {
                $this->redirect(array('/product/index'));
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Displays the register page
     */
    public function actionRegister() {
        $this->layout = '//layouts/site';
        
        $model = new RegisterForm();

        // collect user input data
        if (isset($_POST['RegisterForm'])) {
            $model->attributes = $_POST['RegisterForm'];

            if ($model->validate() && $model->register()) {
                $this->redirect(Yii::app()->createUrl('/user/alert', array('name' => $model->username)));
            }
        }
        // display the register form
        $this->render('register', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}