<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

use app\models\User;
?>
<h1><?=Yii::t('users', 'Users')?></h1>
<a href="<?=$baseUrl?>/admin/default/create" class="btn btn-primary">Создать</a>
<!-- <table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>Id</td>
			<td><?=Yii::t('users', 'Username')?></td>
			<td><?=Yii::t('users', 'Email')?></td>
			<td><?=Yii::t('users', 'Role')?></td>
			<td><?=Yii::t('users', 'Status')?></td>
			<td><?=Yii::t('users', 'CreatedAt')?></td>
			<td><?=Yii::t('users', 'UpdatedAt')?></td>
			<td><?=Yii::t('users', 'Actions')?></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($model as $item): ?>
		<tr>
			<td><?=$item->id?></td>
			<td><?=$item->username?></td>
			<td><?=$item->email?></td>
			<td><?=$item->role?></td>
			<td><?=$item->status?></td>
			<td><?=$item->created_at?></td>
			<td><?=$item->updated_at?></td>
			<td>
				<a href="<?=$baseUrl?>/admin/default/edit/<?=$item->id?>">Редактировать</a>
				|
				<a href="<?=$baseUrl?>/admin/default/delete/<?=$item->id?>">Удалить</a>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table> -->
<?php
$dataProvider = new ArrayDataProvider([
    'key' => 'id',
    'allModels' => $model,
    'sort' => [
        'attributes' => ['id', 'username', 'role', 'status'],
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
            'username',
            'email:ntext',
            // 'role:ntext',
            [
	            'attribute'=>'role',
	            'contentOptions' => function($model, $key, $index, $column){
	            	if ($model->role === User::ROLE_ADMIN)
	            		return ['style'=>'background: #6AD19F; color: #FFFFFF;'];
	            	else
	            		return [];
	            },
	            'content'=>function($data){
	            	// var_dump($data);
	            	switch ($data->role) {
	            		case User::ROLE_USER:
	            			return Yii::t('users', 'User');
	            		case User::ROLE_ADMIN:
	            			return Yii::t('users', 'Admin');	            		
	            		default:
	            			return $data->role;
	            	}
	            	return $data->role;
	            }
	        ],
            'status:ntext',
            'created_at:datetime',
            'updated_at:datetime',
 
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