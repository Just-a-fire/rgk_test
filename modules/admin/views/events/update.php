<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Article;
?>
<h1><?= Yii::t('articles', 'Edit')?></h1>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-md-3">
		<?= $form->field($row, 'title')->textInput()?>
	</div>
	<div class="col-md-3">
		<?= $form->field($row, 'content')->textArea()?>		
	</div>
	<div class="col-md-3">
		<?= $form->field($row, 'published')->dropDownList(Article::statusDescription())?>		
	</div>
	<div class="col-md-12">
		<?= Html::submitButton(Yii::t('articles', 'Change'), ['class' => 'btn btn-success']) ?>
		
	</div>
</div>
<?php ActiveForm::end(); ?>