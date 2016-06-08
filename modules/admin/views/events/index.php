<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

use app\models\User;
use app\models\Notice;
?>
<h1><?=Yii::t('events', 'Events')?></h1>
<?php
$dataProvider = new ArrayDataProvider([
    'key' => 'id',
    'allModels' => $model,
    'sort' => [
        'attributes' => ['id', 'created_at', 'created_by', 'user_id', 'subject'],
    ],
    'pagination' => [
        'pageSize' => 50,
    ],
]);
?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
 
            'id',
            'created_at:datetime',
            // 'created_by',
            [
                'attribute'=>'created_by',
                'content'=>function($data){
                    $user = User::findIdentity($data->created_by);
                    return $user ? $user->username : $data->created_by;
                }
            ],
            // 'user_id',
            [
                'attribute'=>'created_by',
                'content'=>function($data){
                    $user = User::findIdentity($data->created_by);
                    return $user ? $user->username : $data->created_by;
                }
            ],
            'type',
            'description:ntext',
            'model:ntext',
            'item_id',
 
            $actionColumnParams,
        ],
    ]); ?>