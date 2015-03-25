<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property string $email
 * @property string $logintime
 * @property integer $admin
 * @property integer $active
 * @property string $outtime
 */
class User extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public $modifyPassWord;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_user';
    }

    public static function getIdArray() {
        $ids = array();

        $rowdata = Yii::app()->db->createCommand()
                ->from('tbl_user')
                ->select('id')
                ->queryAll();
        foreach ($rowdata as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }

    protected function beforeSave() {
        Yii::import('application.lib.Functions');

        if ($this->isNewRecord) {
            $this->logintime = Functions::getNow();
            if ($this->level)
                $this->active = 1;
        }

        return true;
    }

    public static function modifyPassword($password) {
        $nRet = 0;
        if (isset($password{0})) {
            $password = md5($password);
            $nRet = Yii::app()->db->createCommand()
                    ->update('tbl_user', array('password' => $password), 'id=' . Yii::app()->user->id);
        }
        return $nRet > 0;
    }

    public static function findByUsername($username) {
        return Yii::app()->db->createCommand()
                        ->from('tbl_user')
                        ->select('*')
                        ->where('username=:username', array(':username' => $username))
                        ->queryRow();
    }
    
    public static function getUserNick($id) {
        $row = Yii::app()->db->createCommand()
                        ->from('tbl_user')
                        ->select('nickname')
                        ->where('id=:id', array(':id' => $id))
                        ->queryRow();
        if($row !== false) return $row['nickname'];
        
        return false;
    }

    public static function findUserSession($id = 0) {
        if ($id === 0)
            $id = Yii::app()->user->id;
        $row = Yii::app()->db->createCommand()
                ->from('tbl_user')
                ->select('topsession')
                ->where('id=:id', array(':id' => $id))
                ->queryRow();
        if (isset($row['topsession']))
            return $row['topsession'];
        return false;
    }

    public static function getUserName($id = 0) {
        if ($id === 0)
            $id = Yii::app()->user->id;
        $row = Yii::app()->db->createCommand()
                ->from('tbl_user')
                ->select('username')
                ->where('id=:id', array(':id' => $id))
                ->queryRow();
        if (isset($row['username']))
            return $row['username'];
        return false;
    }

    public static function exsitUsername($username) {
        $row = User::findByUsername($username);
        return isset($row['id']);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('level, active, jms', 'numerical', 'integerOnly' => true),
            array('password', 'length', 'max' => 32),
            array('username, nickname, email', 'length', 'max' => 50),
            array('userID, sex, jms,logintime, outtime,w2expires,refresh_token', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('userID, id,sex, jms, username, password, nickname, email, logintime, level, active, outtime, topsession', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'orders' => array(self::HAS_MANY, 'Order', 'user_id, product_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'sex' => '性别',
            'id' => 'id',
            'username' => '用户名',
            'password' => '密码',
            'email' => '邮件地址',
            'logintime' => '注册时间',
            'level' => '用户权限',
            'active' => '激活',
            'outtime' => '淘宝授权失效时间',
            'userID' => '淘宝ID',
            'topsession' => '淘宝授权',
            'nickname' => '昵称',
            'jms' => '消息服务',
            'refresh_token'=>'refresh_token',
            'w2expires'=>'w2expires'
        );
    }

    public function isActive() {
        return $this->active == 1;
    }

    public function getLevelDes() {
        switch ($this->level) {
            case 0:
                return '淘宝用户';
                break;
            case 1:
                return '授权用户';
                break;
            case 2:
                return '网站管理员 ';
                break;
            default:
                return '未知用户';
        }
    }

    public function getJmsDes() {
        if ($this->jms == 1) {
            return '<span class="label label-success">是</span>';
        }
        return '<span class="label label-warning">否</span>';
    }

    protected function getActiveState($data, $row) {
        if ($data->active == 1) {
            return '<span class="label label-success">是</span>';
        }
        return '<span class="label label-warning">否</span>';
    }

    protected function getLevelState($data, $row) {
        $des = '';
        switch ($data->level) {
            case 0:
                $des = '淘宝用户';
                break;
            case 1:
                $des = '超级用户';
                break;
            case 2:
                $des = '网站管理员 ';
                break;
            default:
                $des = '未知用户';
        }
        return "<span class='label label-success'>{$des}</span>";
    }

    public function getPasswordBtn() {
        return CHtml::button('修改密码', array('id' => 'modifyPassword', 'class' => 'btn'));
    }
    
    public static function checkW2TimeOut(){
        $row = Yii::app()->db->createCommand()
                        ->from('tbl_user')
                        ->select('w2expires,refresh_token')
                        ->where('id=:id', array(':id' => Yii::app()->user->id))
                        ->queryRow();
        Yii::import('application.lib.Functions');
        if($row == false || Functions::isTimeOut($row['w2expires']))
            return false;
        $userinfo = array();
        $userinfo['url'] = Yii::app()->params['token_url'];
        $userinfo['refresh_token'] = $row['refresh_token'];
        $userinfo['secret'] = Yii::app()->params['AppSecret'];
        $userinfo['id'] = Yii::app()->params['AppKey'];
        return $userinfo;
    }

    public function showWarningMessage() {
        Yii::import('application.lib.Functions');

        $message = '请在项目管理中设置您上传的配置。';
        if ($this->level == 0)
            $message .= '您为淘宝普通用户，请您申请激活用户，以便使用更多功能！';

        if (!isset($this->topsession) || !isset($this->topsession{0})) {
            $message .= '<p>未进行淘宝授权，请及时重新授权!</p>';
        } elseif (!isset($this->outtime) || $this->outtime == 0) {
            $message .= '<p>淘宝授权时间过期，请及时重新授权!</p>';
        } elseif (Functions::isTimeCloseHalfHour($this->outtime)) {
            $message .= '<p>淘宝授权时间即将过期，请及时重新授权!</p>';
        } else {
            $message .= '<p>您的淘宝授权处于最佳状态。</p>';
        }

        if ($this->jms == 0) {
            $message .= '<p>你的消息服务未开通，请开通商品删除和商品交易消息服务！</p>';
        }

        return "<div class='alert alert-block'>
        <h4 class='alert-heading'>信息提示</h4>
        {$message}
      </div>";
    }

    public static function findUserByPk($pk, $condition = '', $params = array()) {
        $model = parent::model(__CLASS__)->findByPk($pk);
        if ($model !== null)
            $model->modifyPassWord = $model->password;

        return $model;
    }

    public function save($runValidation = true, $attributes = NULL) {

        if ($this->modifyPassWord !== null && md5($this->password) !== $this->modifyPassWord) {
            $this->password = md5($this->password);
        } elseif (isset($this->password{0}) && $this->isNewRecord) {
            $this->password = md5($this->password);
        }

        return parent::save($runValidation, $attributes);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('nickname', $this->nickname, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('logintime', $this->logintime, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('active', $this->active);
        $criteria->compare('userID', $this->userID);
        $criteria->compare('outtime', $this->outtime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}