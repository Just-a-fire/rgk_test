<?php
namespace app\modules\admin\controllers;

use yii\base\NotSupportedException;
use yii\web\Controller;
use yii\helpers\Url;
use yii;
use app\models\Notice;
use app\models\Event;
use app\models\User;


use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
* 
*/
class EventsController extends Controller
{

	public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','view'], // 'create','update' denied
                'rules' => [
                    // разрешение для админов
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    // для всех остальных запрет
                ],
            ],            
        ];
    }

	public function actionIndex()
	{
		$model = Event::getAll();
		$baseUrl = \Yii::$app->components['urlManager']['baseUrl'];

		$actionColumnParams = [
	    	'class' => 'yii\grid\ActionColumn',
	    	'header' => Yii::t('users', 'Actions'),
	    	'template' => '{view}',
	    ];
		// $actionColumnTemplate = Yii::$app->user->can('admin') ? '{view} {update} {link}' : '{view}'; // TODO: Del?
		return $this->render('index', ['model' => $model, 'baseUrl' => $baseUrl, 'actionColumnParams' => $actionColumnParams]);
	}

	public function actionCreate()
	{
		throw new NotSupportedException('"actionCreate" is denied.');
	}

	public function actionView($id)
	{
		$row = Event::findIdentity($id);
		$returnUrl = Url::home() . 'admin/events';

		return $this->render('view', ['row' => $row, 'returnUrl' => $returnUrl]);
	}

	public function actionUpdate($id)
	{
		throw new NotSupportedException('"actionUpdate" is denied.');
	}

}
?>