<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

use app\models\Article;
?>
<h1><?=Yii::t('articles', 'Articles')?></h1>
<a href="<?=$baseUrl?>/admin/articles/create" class="btn btn-primary">Создать</a>
<?php
$dataProvider = new ArrayDataProvider([
    'key' => 'id',
    'allModels' => $model,
    'sort' => [
        'attributes' => ['id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'title'],
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
            'updated_at:datetime',
            'created_by',
            'updated_by',
            'title:ntext',
            'content:ntext',
            [
	            'attribute'=>'published',
	            'contentOptions' => function($model, $key, $index, $column){
	            	if ($model->published === Article::STATUS_PUBLISHED)
	            		return ['style'=>'background: #6AD19F; color: #FFFFFF;'];
	            	else if ($model->published === Article::STATUS_UNPUBLISHED)
	            		return ['style'=>'background: #D16A9F; color: #FFFFFF;'];
	            	else
	            		return [];
	            },
	            'content'=>function($data){
	            	// var_dump($data);
	            	switch ($data->published) {
	            		case Article::STATUS_PUBLISHED:
	            			return Article::statusDescription()[$data->published];
	            		case Article::STATUS_UNPUBLISHED:
	            			return Article::statusDescription()[$data->published];	            		
	            		default:
	            			return $data->published;
	            	}
	            	return $data->published;
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