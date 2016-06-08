<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

use app\models\User;
use app\models\Notice;
?>
<h1><?=Yii::t('notice', 'Notice')?></h1>
<a href="<?=$baseUrl?>/admin/notice/create" class="btn btn-primary">Создать</a>
<?php
$dataProvider = new ArrayDataProvider([
    'key' => 'id',
    'allModels' => $model,
    'sort' => [
        'attributes' => ['id', 'created_at', 'created_by', 'user_id', 'subject'],
    ],
    'pagination' => [
        'pageSize' => 20,
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
                'attribute'=>'user_id',
                'content'=>function($data){
                    if ($data->user_id == Notice::ALL) return 'all';
                    $user = User::findIdentity($data->user_id);
                    return $user ? $user->username : $data->user_id;
                }
            ],
            'subject:ntext',
            'content:ntext',
            [
	            'attribute'=>'type',
	            // 'contentOptions' => function($model, $key, $index, $column){
	            // 	if ($model->type === Article::STATUS_PUBLISHED)
	            // 		return ['style'=>'background: #6AD19F; color: #FFFFFF;'];
	            // 	else if ($model->published === Article::STATUS_UNPUBLISHED)
	            // 		return ['style'=>'background: #D16A9F; color: #FFFFFF;'];
	            // 	else
	            // 		return [];
	            // },
	            'content'=>function($data){
                    $types = [];
                    $type_description = Notice::typeDescription();
                    foreach ($type_description as $key => $value) {
                        if ($data->type & $key)
                            $types[] = $value; 
                    }

	            	return join(',', $types);
	            }
	        ],
 
            [
            	'class' => 'yii\grid\ActionColumn',
            	'header' => Yii::t('users', 'Actions'),
            	'template' => '{view} {update} {link}', // {delete} убрал
            	'buttons' => [	            	
	            	'update' => function ($url, $model, $key) {
	                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('users', 'Edit'),
                        ]);
	                }
            	],
            ],
        ],
    ]); ?>