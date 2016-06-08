<?php
use yii\helpers\Html;
use app\models\User;

$this->title = $row->description;
// $this->params['breadcrumbs'][] = $this->title;
$author = User::findIdentity($row->created_by);
?>
<div class="admin-article">
	<p>
		<?=Yii::t('articles', 'Author')?>: <?=$author ? $author->username : $row->created_by?> | 
		<?=Yii::t('articles', 'Created')?>: <?=$row->created_at?>
	</p>

    <h1><?= Html::encode($row->type) ?></h1>

    <p>
        <?= Html::encode($row->description) ?>
    </p>

    <?= Html::a(Yii::t('articles', 'To articles list'), $returnUrl) ?>
</div>