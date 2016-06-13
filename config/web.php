<?php

$params = require(__DIR__ . '/params.php');

use \yii\web\Request;
$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'UpIy-3Xq-pIiXBTpjBFqS7zMZrxz76-r',
            'baseUrl' => $baseUrl,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        
        'urlManager' => [
            'baseUrl' => $baseUrl,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<action>'=>'site/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                // '<module:[\wd-]+>/<controller:[\wd-]+>/<action:[\wd-]+>/<id:\d+>] => <module>/<controller>/<action>',
            ],
        ],

        // Интернационализация
        'i18n' => [
            'translations' => [
                'form' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__DIR__) . '/common/messages',
                    'fileMap' => [
                        'form' => 'form.php',
                    ]
                ],
                'notice' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__DIR__) . '/common/messages',
                    'fileMap' => [
                        'notice' => 'notice.php',
                    ]
                ],
                'mail' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__DIR__) . '/common/messages',
                    'fileMap' => [
                        'mail' => 'mail.php',
                    ]
                ],
                'users' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__DIR__) . '/common/messages',
                    'fileMap' => [
                        'users' => 'users.php',
                    ]
                ],
                'articles' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__DIR__) . '/common/messages',
                    'fileMap' => [
                        'articles' => 'articles.php',
                    ]
                ],
                'events' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__DIR__) . '/common/messages',
                    'fileMap' => [
                        'events' => 'events.php',
                    ]
                ],
            ],
        ],

        // Почта
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // 'transport' => [
            //     'class' => 'Swift_SmtpTransport',
            //     'host' => 'smtp.mail.ru',
            //     'username' => '21ivan777@mail.ru',
            //     'password' => '12332155asd',
            //     'port' => '587',
            //     'encryption' => 'tls',
            // ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'albertrgk@gmail.com',
                'password' => 'pass21pass21',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],

        // Доступ ролей
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['user','admin'], //здесь прописываем роли
            //зададим куда будут сохраняться наши файлы конфигураций RBAC
            'itemFile' => dirname(__DIR__) . '/common/components/rbac/items.php',
            'assignmentFile' => dirname(__DIR__) . '/common/components/rbac/assignments.php',
            'ruleFile' => dirname(__DIR__) . '/common/components/rbac/rules.php'
        ],

        // custom sender
        'sender' => [
            'class' => 'app\custom\sender\Sender',
            'paths' => [
                'articles' => [
                    'view' => '/user/articles/view/',
                ]
            ],
        ],
        
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
