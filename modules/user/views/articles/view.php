<?php
use yii\helpers\Html;
use app\models\User;

$this->title = $row->title;
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-article">
	<p>
		<?=Yii::t('articles', 'Author')?>: <?=User::findIdentity($row->created_by)->username?> | 
		<?=Yii::t('articles', 'Created')?>: <?=$row->created_at?>
	</p>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::encode($row->content) ?>
    </p>
</div>