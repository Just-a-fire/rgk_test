<?php
namespace app\modules\user\controllers;

use yii\web\Controller;
use yii;
use app\models\Article;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
* 
*/
class ArticlesController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','create', 'update', 'delete'],
                'rules' => [
                    // разрешение для админов
                    [
                    	'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                       'actions' => ['index'],
                       'allow' => false,
                       // Allow moderators and admins to update
                       'roles' => ['*'],
                   ],
                    // для всех остальных запрет
                ],
            ],            
        ];
    }

	// public function accessRules()
 //    {
 //        return array(
 //            array('deny',
 //                'actions'=>array('*'),
 //                'roles'=>array('@'),
 //            ),
 //        );
 //    }

	// public function beforeAction($action) {
	// 	if (!Yii::$app->user->isGuest && Yii::$app->user->can('admin')) {
	//     	return parent::beforeAction($action);
	// 	}
	// 	return false;
	// }

	public function actionIndex()
	{
		$model = Article::getAll();
		$baseUrl = \Yii::$app->components['urlManager']['baseUrl'];
		// $actionColumnTemplate = Yii::$app->user->can('admin') ? '{view} {update} {link}' : '{view}'; // TODO: Del?
		return $this->render('index', ['model' => $model, 'baseUrl' => $baseUrl]);
	}

	public function actionCreate()
	{
		throw new NotSupportedException('"actionCreate" is denied.');
	}

	public function actionView($id)
	{
		$row = Article::findIdentity($id);
		// $returnUrl = Url::home() . 'admin/articles';

		return $this->render('view',['row' => $row]);
	}

	public function actionUpdate($id)
	{
		throw new NotSupportedException('"actionUpdate" is denied.');
	}

}
?>