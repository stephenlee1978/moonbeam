<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
$frontend = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';
Yii::setPathOfAlias('widgets', $frontend . DIRECTORY_SEPARATOR . 'widgets');
Yii::setPathOfAlias('lib', $frontend . DIRECTORY_SEPARATOR . 'lib');
Yii::setPathOfAlias('rootpath', dirname(dirname(dirname(__FILE__))));
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Moonbeam',
    'language' => 'zh_cn',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.lib.*',
        'application.lib.http.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'feng',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'authManager'=>array(
            'class' => 'CDbAuthManager',
            'connectionID'=>'db',
            'defaultRoles'=>array('guest'),
        ),
        'cache' => array(
            /*'class' => 'system.caching.CFileCache',
            'directoryLevel' => '2',*/
            
            'class' => 'system.caching.CMemCache',   
            'servers' => array( array('host' => 'localhost', 'port' => 11211)),  
            'keyPrefix' => '',   
            'hashKey' => false,   
            'serializer' => false 
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'StoreUser',
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                'gii' => 'gii',
            ),
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=moon',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'feng',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
            'schemaCachingDuration'=>10
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, info',
                ),
            // uncomment the following to show log messages on web pages
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['attributevalues']
);