<?php
Yii::import('ext.captchaExtended.CaptchaExtendedValidator');
class RegisterForm extends CFormModel {

    public $id;
    public $username;
    public $password;
    public $repassword;
    public $nickname;
    public $email;
    public $verifyCode;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('username', 'validateUsername'),
            array('username, password, repassword,verifyCode', 'required', 'message' => '{attribute} 不能为空.'),
            array('password', 'compare', 'compareAttribute' => 'repassword', 'operator' => '==', 'message' => '密码不相同.'),
            array('nickname, email', 'default', 'setOnEmpty' => true, 'value' => NULL),
            //验证码需填写
            array('verifyCode', 'CaptchaExtendedValidator', 'allowEmpty' => !CCaptcha::checkRequirements(), 'message' => '{attribute} 不正确.'),
        );
    }

    public function validateUsername($attribute, $params){
        if (User::exsitUsername($this->username)) {
            $this->addError('username', '用户名已经存在');
        }
    }
    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => '用户名称',
            'nickname' => '昵称',
            'password' => '密码',
            'repassword' => '重复密码',
            'email' => '邮箱地址',
            'verifyCode' => '填写验证码 ',
            'id' => 'ID',
        );
    }

    /**
     * Register user.
     * @return boolean whether Register is successful
     */
    public function register() {
        $user = new User;
        $user->username = $this->username;
        $user->nickname = $this->nickname;
        $user->password = md5($this->password);
        $user->email = $this->email;
        $user->level = 1;

        return $user->save();
    }

}
