<?php
namespace app\modules\admin\controllers;

use yii\web\Controller;
use yii;
use app\models\MyList;
use app\models\User;

/**
* 
*/
class DefaultController extends Controller
{
	
	public function actionIndex()
	{
		$array = MyList::getAll();
		$baseUrl = \Yii::$app->components['urlManager']['baseUrl'];
		return $this->render('index',['model'=>$array,'baseUrl'=>$baseUrl]);
	}

	public function actionEdit($id)
	{
		$one = MyList::getOne($id);

		if (isset($_POST['MyList'])) {
			$one->title = $_POST['MyList']['title'];
			$one->description = $_POST['MyList']['description'];
			// $one->attributes = $_POST['MyList'];
			if ($one->validate() && $one->save()) {
				return $this->redirect(['index']);
			}
		}

		return $this->render('edit',['one'=>$one]);
	}

	public function actionCreate()
	{
		$model = new MyList();

		if (isset($_POST['MyList'])) {
			$model->title = $_POST['MyList']['title'];
			$model->description = $_POST['MyList']['description'];
			// $one->attributes = $_POST['MyList'];
			if ($model->validate() && $model->save()) {
				return $this->redirect(['index']);
			}
		}

		return $this->render('create',['model'=>$model]);
	}

	public function actionDelete($id)
	{
		$model = MyList::getOne($id);
		if ($model !== NULL) {				
			$model->delete();
			return $this->redirect(['index']);
		}
	}
}
?>