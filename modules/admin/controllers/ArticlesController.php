<?php
namespace app\modules\admin\controllers;

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
                // 'only' => ['index','create','update','view'],
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
		$model = new Article();

		if (isset($_POST['Article'])) {
			$model->title = $_POST['Article']['title'];
			$model->content = $_POST['Article']['content'];
			// $model->created_by = \Yii::$app->user->id;
			// $one->attributes = $_POST['Article'];
			if ($model->validate() && $model->save()) {
				return $this->redirect(['index']);
			}
		}
		return $this->render('create', ['model' => $model]);
	}

	public function actionView($id)
	{
		$row = Article::findIdentity($id);
		$returnUrl = Url::home() . 'admin/articles';

		return $this->render('view',['row' => $row, 'returnUrl' => $returnUrl]);
	}

	public function actionUpdate($id)
	{
		$row = Article::findIdentity($id);

		if (isset($_POST['Article'])) {
			// $row->attributes = $_POST['Article'];
			foreach ($_POST['Article'] as $key => $value) {
				if ($key === 'password')
					if ($value !== '')
						$row->setPassword($value);
					else
						continue;
				else
					$row->$key = $value;
			}
			if ($row->validate() && $row->save()) {
				return $this->redirect(['index']);
			}
		}
		return $this->render('update',['row' => $row]);
	}

}
?>