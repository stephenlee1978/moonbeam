<?php

//siteuser
class StoreUser extends CWebUser {

    CONST MENU_ITEM = "_MENU_ITEM";

    public $error = array();

    public function init() {
        parent::init();

        $this->guestName = '游客';
    }

    public function getIsGuest() {  
        if ($this->hasState('__user')) {
            return false;
        }
        return true;
    }  

    public function __get($name) {
        if ($this->hasState('__user')) {
            $user = $this->getState('__user');

            if (isset($user[$name])) {
                return $user[$name];
            }
        }

        return parent::__get($name); ;
    }
    
    public function getId() {
        if ($this->hasState('__user')) {
            $user = $this->getState('__user');

            if (isset($user['id'])) {
                return $user['id'];
            }
        }

        return null;
    }

    public function isAdministrator(){
        if($this->hasState('__user')){
            $user = $this->getState('__user');
            if (isset($user['level']) && $user['level'] == '2') {
                return true;
            }
        }
        return false;
    }
    
    public function login($identity, $duration = 0) {
        $this->setState('__user', $identity->getUser());

        parent::login($identity, $duration);
    }

    //淘宝授权
    public function authorization($tokenInfo) {
        if (!isset($tokenInfo->taobao_user_id)) {
            $this->error['code'] = 12;
            $this->error['message'] = '授权TOKEN数据错误';
        }

        if (Yii::app()->user->isGuest){
            return $this->newTaobaoLogin($tokenInfo);
        }else{
            return $this->userAuthorization($tokenInfo);
        }

        return false;
    }

    //新淘宝用户注册
    public function newTaobaoLogin($tokenInfo) {

        Yii::import('application.lib.Functions');

        $isNewRecord = false;
        $user = User::model()->findByAttributes(array('userID' => $tokenInfo->taobao_user_id));
        if ($user === null) {
            $user = new User;
            $user->username = urldecode($tokenInfo->taobao_user_nick);
            $user->password = "888888";
            $user->nickname = urldecode($tokenInfo->taobao_user_nick);
            $user->userID = $tokenInfo->taobao_user_id;
            $user->level = 0;
            $isNewRecord = true;
        } 
        $user->outtime = Functions::getOutTime($tokenInfo->w1_expires_in);
        $user->topsession = $tokenInfo->access_token;
        $user->w2expires = Functions::getOutTime($tokenInfo->w2_expires_in);
        $user->refresh_token = $tokenInfo->refresh_token;
        if (!$user->save()) {
            throw new CHttpException(14, '用户信息保存失败');
            return false;
        }elseif($isNewRecord){
            Assignments::addNewTaobaoUser($user->id);
        }
        $this->saveUserLoginInfo($user);
        return true;
    }

    //新淘宝用户注册
    public function userAuthorization($tokenInfo) {
        $this->error = array();

        Yii::import('application.lib.Functions');
        $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
        if ($user === null)
            throw new CHttpException(1, '用户查找异常!');

        if ($user->userID != $tokenInfo->taobao_user_id && isset($user->userID{0})) {
            throw new CHttpException(2, '不允许一个用户授权多个淘宝用户！');
            return false;
        }

        $user->nickname = urldecode($tokenInfo->taobao_user_nick);
        $user->userID = $tokenInfo->taobao_user_id;
        $user->outtime = Functions::getOutTime($tokenInfo->w1_expires_in);
        $user->topsession = $tokenInfo->access_token;
        $user->w2expires = Functions::getOutTime($tokenInfo->w2_expires_in);
        $user->refresh_token = $tokenInfo->refresh_token;
        $this->tmcUserPermit($user);
        if (!$user->save()) {
            throw new CHttpException(3, '用户信息保存失败！');
            return false;
        }
       
        $this->updateUserLoginInfo($user);
        return true;
    }
    
    private function updateUserLoginInfo($user){
        $this->setState('__user', $user->getAttributes());
    }

    //专用于淘宝用户
    private function saveUserLoginInfo($user) {
        
        $identity = new UserIdentity($user->username, $user->password);
        $identity->setUser($user);
        $this->login($identity, 3600 * 24 * 1);
    }
    
    private function tmcUserPermit($user){
        if($user->jms == 0){
            Yii::import('lib.Taobao');
            if(Taobao::tmcUserPermit($user->topsession))
                $user->jms = 1;
        }
    }

    public function refreshToken(){
        $userinfo = User::checkW2TimeOut();
        if($userinfo !== false){
            Yii::import('application.lib.http.Auth');
            $auth = new Auth();
            $response = $auth->refreshToken($userinfo);
            if($response !== false){
                $user = User::model()->findByPk(Yii::app()->user->id);
                if($user !== null){
                    $user->outtime = Functions::getOutTime($response->w1_expires_in);
                    $user->topsession = $response->access_token;
                    $user->w2expires = Functions::getOutTime($response->w2_expires_in);
                    $user->refresh_token = $response->refresh_token;
                    $user->save();
                }
            }
        }
    }
    
    public function getCurrUserSession() {
        
        $topsession = User::findUserSession();
        if ($topsession === false)
            return false;

        return $topsession;
    }

}
?>