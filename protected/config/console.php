<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$frontend = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';
Yii::setPathOfAlias('lib', $frontend . DIRECTORY_SEPARATOR . 'lib');
Yii::setPathOfAlias('rootpath', dirname(dirname(dirname(__FILE__))));
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'moonbean',
    // preloading 'log' component
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.lib.*',
		'application.commands.*',
    ),
    // application components
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=moon',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'feng',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        'cache' => array(
            'class' => 'system.caching.CFileCache',
            'directoryLevel' => '2',
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'StoreUser',
        ),
        'authManager'=>array(
            'class' => 'CDbAuthManager',
            'connectionID'=>'db',
            'defaultRoles'=>array('guest'),
        ),
        'commandMap' => array(
            'collect' => array(
                'class' => 'application.commands.CollectCommand',
            ),
            'clear' => array(
                'class' => 'application.commands.ClearCommand',
            ),
			'rbac' => array(
                'class' => 'application.commands.RbacCommand',
            ),
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
);