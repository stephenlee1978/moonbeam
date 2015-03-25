<?php
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 * fix 2014-12-01
 */
class LoginForm extends CFormModel {

    public $username;
    public $password;
    public $rememberMe=true;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('username, password', 'required', 'message' => '{attribute} 不能为空.'),
            // password needs to be authenticated
            array('password', 'authenticate'),
            //记住用户
            array('rememberMe', 'boolean'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => '用户 ',
            'password' => '密码 ',
            'rememberMe' => '自动登陆 ',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);

            $error = $this->_identity->authenticate();

            if ($error === UserIdentity::ERROR_USERNAME_INVALID) {
                $this->addError('username', '用户名错误.');
            } else if ($error === UserIdentity::ERROR_PASSWORD_INVALID) {
                $this->addError('password', '密码错误.');
            } else if ($error === UserIdentity::ERROR_USER_UNACTIVE) {
                $this->addError('password', '该用户未激活,请联系管理原.');
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 15 : 0; // 15 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }

        return false;
    }

    //淘宝用户登录
    public function taobaoLogin() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, "888888");
        }
 
        $user = User::model()->findByAttributes(array('username' => $this->username));
        if($user === null) return false;
        
        $this->_identity->setUser($user);
        Yii::app()->user->login($this->_identity, 3600 * 24 * 15);
        return true;

        return false;
    }

    //是否存在TOPSESSION
    public function isActiveSession() {
        $isActive = false;

        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
        }

        $isActive = $this->_identity->isActiveSession();

        unset($this->_identity);

        return $isActive;
    }

}
