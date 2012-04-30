<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Blog5',
	'defaultController'=>'post',
	'theme'=>'classic',
	'language'=>'en',
		
		

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'blog5',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
        'yiiadmin'=>array(
                'password'=>'cc',
                'registerModels'=>array(
                    'application.models.Contests',
                    //'application.models.BlogPosts',
                    'application.models.*',
                ),
                'excludeModels'=>array(),
            ),
		'admin',
		
	
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            'loginUrl'=>array('/'),
		),
         'image'=>array(
          'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            'params'=>array('directory'=>'/opt/local/bin'),
        ),
        'email'=>array(
            'class'=>'application.extensions.email.Email',
            'delivery'=>'php',
        ),  

		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
// 				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
// 				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
// 				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				
			),
		),
        /*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
         */
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=yiiblognew3',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'public',
			'charset' => 'utf8',
		),
        'authMannager'=>array(
            'class'=>'CDbAuthMannage',
            'connectionID'=>'db',
        ),
// 		'authManager'=>array(
// 				'modules.srbac.components.SDbAuthManager'
// 				),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),			
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
					//在WEB页面查看SQL语句配置
// 				array(
// 					'class'=>'CWebLogRoute',
// 						'levels'=>'trace',     //级别为trace
// 						'categories'=>'system.db.*' //只显示关于数据库信息,包括数据库连接,数据库执行语句
// 				),
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>
		// this is used in contact page
       require(dirname(__FILE__).'/params.php'),
			
	//),
);
