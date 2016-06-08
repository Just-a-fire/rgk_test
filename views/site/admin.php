<?php

use yii\helpers\Html;

$this->title = 'Admin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-admin">
	<div class="row">
		<div class="col-md-12">
			<?= Html::a('Статьи', 'admin/articles') ?>
		</div>
		<div class="col-md-12">
			<?= Html::a('Пользователи', 'admin/users') ?>
		</div>
		<div class="col-md-12">
			<?= Html::a('События', 'admin/events') ?>			
		</div>
		<div class="col-md-12">
			<?= Html::a('Уведомления', 'admin/notice') ?>			
		</div>
	</div>
</div>