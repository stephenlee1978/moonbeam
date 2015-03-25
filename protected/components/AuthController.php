<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AuthController extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    public function init() {
        // register class paths for extension captcha extended
        Yii::$classMap = array_merge(Yii::$classMap, array(
            'CaptchaExtendedAction' => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
            'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
        ));
    }
    
    public function filterAccessControl($filterChain){
       if(Yii::app()->user->isGuest){
           $this->redirect(array('/site/login'));
       }
        $filterChain->run(); 
    }
    
    public function filters() {
        return array( 
            'accessControl',
        );
    }

}