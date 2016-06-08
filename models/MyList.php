<?php

namespace app\models;


/**
* 
*/
class MyList extends \yii\db\activeRecord
{
	
	public static function tableName()
	{
		return 'list';
	}

	public static function getAll()
	{
		$connection=new \yii\db\Connection([
			'dsn' => 'mysql:host=localhost;dbname=yii2basic',
		    'username' => 'root',
		    'password' => '',
		    'charset' => 'utf8',
		]);
		// $connection->active=true;
		// $sql='SELECT * FROM list';
		// $command=$connection->createCommand($sql);
		// $dataReader=$command->query();
		// var_dump($dataReader->read());

		// $qq = new \Yii();

		// $ww = \Yii::$app->getModules();

		// var_dump($ww);

		// $pp = \Yii::$app->components['urlManager']['baseUrl'];
		// var_dump($pp);

		// $aa = \Yii::$app->user;
		// var_dump($aa);

		// var_dump(\Yii::$app->i18n->translations);

		// var_dump($qq::$app->db);
		// $command=$qq::$app->db->createCommand('set names utf8');
		// $dataReader=$command->query();
		// $command=$qq::$app->db->createCommand($sql);
		// $dataReader=$command->query();
		// var_dump($dataReader->read());

		// var_dump(get_class_vars( get_class( $qq::$app ) ) );


		$data = self::find()->all();

		return $data;
	}

	public static function getOne($id)
	{
		$data = self::find()
			->where(['id' => $id])
			->one();

		return $data;
	}
}
?>
