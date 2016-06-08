<?php
namespace app\modules\admin\controllers;

use yii\web\Controller;
use yii;
use app\models\User;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
* 
*/
class UsersController extends Controller
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

	public function actionIndex()
	{
		$model = User::getAll();
		$baseUrl = \Yii::$app->components['urlManager']['baseUrl'];
		return $this->render('index', ['model' => $model, 'baseUrl' => $baseUrl]);
	}

	public function actionCreate()
	{
		return $this->render('create');
	}

	public function actionView()
	{
		return $this->render('view');
	}

	public function actionUpdate($id)
	{
		$row = User::findIdentity($id);

		if (isset($_POST['User'])) {
			// $row->attributes = $_POST['User'];
			foreach ($_POST['User'] as $key => $value) {
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