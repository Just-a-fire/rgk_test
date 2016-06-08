<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\User;
?>
<h1><?= Yii::t('users', 'Edit')?></h1>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-md-3">
		<?= $form->field($row, 'username')->textInput()?>
	</div>
	<div class="col-md-3">
		<?= $form->field($row, 'email')->textInput()?>		
	</div>
	<div class="col-md-3">
		<?= $form->field($row, 'password')->textInput(['value' => '', 'placeholder' => Yii::t('users', 'Password will be hashed')])?>		
	</div>
	<div class="col-md-3">
		<?= $form->field($row, 'role')->dropDownList(User::roleDescription())?>		
	</div>
	<div class="col-md-3">
		<?= $form->field($row, 'status')->dropDownList(User::statusDescription())?>		
	</div>
	<div class="col-md-3">
		<?= $form->field($row, 'created_at')->textInput(['readonly' => true])?>		
	</div>
	<div class="col-md-3">
		<?= $form->field($row, 'updated_at')->textInput(['readonly' => true])?>		
	</div>
	<div class="col-md-12">
		<?= Html::submitButton('Изменить', ['class' => 'btn btn-success']) ?>
		
	</div>
</div>
<?php ActiveForm::end(); ?>