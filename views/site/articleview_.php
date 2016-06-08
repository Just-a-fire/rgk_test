<?php

/* @var $this yii\web\View */
// use yii;
use yii\helpers\Html;

$this->title = Yii::t('notice','Notice');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-notice">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>There are <?=$value?> years годы</p>
	
	<ul>
	<?php foreach ($arrayInView as $key => $value) { ?>
		<li><?=$value->title?></li>
	<?php } ?>
	</ul>
</div>

<?php var_dump(yii\helpers\BaseUrl::base(true)) ?>
<?php var_dump(Yii::$app->session->getFlash('signupFormSubmitted')); ?>
<?php var_dump(Yii::$app->session->getFlash('signupFormSubmitted_id')); ?>
<?php var_dump(Yii::$app->session->getFlash('signupFormSubmitted_PrimaryKey')); ?>