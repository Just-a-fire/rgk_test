<?php
namespace app\modules\admin\controllers;

use yii\base\NotSupportedException;
use yii\web\Controller;
use yii;
use app\models\Notice;
use app\models\Event;
use app\models\User;


use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
* 
*/
class NoticeController extends Controller
{

	public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','create','view'], // 'update' denied
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
		$model = Notice::getAll();
		$baseUrl = \Yii::$app->components['urlManager']['baseUrl'];
		// $actionColumnTemplate = Yii::$app->user->can('admin') ? '{view} {update} {link}' : '{view}'; // TODO: Del?
		return $this->render('index', ['model' => $model, 'baseUrl' => $baseUrl]);
	}

	public function actionCreate()
	{
		$model = new Notice();
		$baseUrl = \Yii::$app->components['urlManager']['baseUrl'];
		
		// $user_list = array_merge([0 => Yii::t('notice', 'To all')], User::find()->select(['username', 'id'])->indexBy('id')->asArray()->column());
		$user_list = [0 => Yii::t('notice', 'To all')] + User::find()->select(['username', 'id'])->indexBy('id')->asArray()->column();
		$type_list = Notice::typeDescription();
		// $event_list = Event::::find()->select(['username', 'id'])->indexBy('id')->asArray()->column();


		if (isset($_POST['Notice'])) {
			$model->attributes = $_POST['Notice'];
			foreach ($_POST['Notice'] as $key => $value) {
				if ($key === 'type') {
					$model->$key = 0x0000;
					if (!$value) continue;
					foreach ($value as $flag) {
						$model->$key |= intval($flag);
					}		
				} else {					
					$model->$key = $value;
				}
			}
			if ($model->validate() && $model->save()) {
				return $this->redirect(['index']);
			}
		}
		return $this->render('create', ['model' => $model, 'baseUrl' => $baseUrl, 'user_list' => $user_list, 'type_list' => $type_list]);
	}

	public function actionView($id)
	{
		$row = Notice::findIdentity($id);
		$returnUrl = Url::home() . 'admin/Notice';

		return $this->render('view', ['row' => $row, 'returnUrl' => $returnUrl]);
	}

	public function actionUpdate($id)
	{
		throw new NotSupportedException('"actionUpdate" is denied.');
	}

}
?>