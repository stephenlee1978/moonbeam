<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */

    const ERROR_USER_UNACTIVE = 3;

    public $user;
   
    //验证
    public function authenticate() {
        $user = User::model()->findByAttributes(array('username' => $this->username));

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($user->password !== md5($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else if (!$user->isActive()) {
            $this->errorCode = self::ERROR_USER_UNACTIVE;
        } else {
            $this->setUser($user);
            $this->errorCode = self::ERROR_NONE;
        }
        unset($user);

        return $this->errorCode;
    }
    
//    得到用户信息
    public function getUser() {
        return $this->user;
    }

//    设置用户属性
    public function setUser(User $user) {
        $this->user = $user->getAttributes();
    }

    //原有淘宝用户登录
    public function taobaoLogin() {
        $user = User::model()->findByAttributes(array('nickname' => $this->username));
        if ($user !== null) {
            Yii::app()->user->saveUserLoginInfo($user);
            return true;
        }else{
            
        }

        return false;
    }

}