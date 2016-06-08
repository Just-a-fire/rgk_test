<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
?>
<h1><?=Yii::t('notice', 'Create')?></h1>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-md-6">
		<?= //$form->field($model, 'event_id')->dropDownList($event_list)
		$form->field($model, 'event_id')->textInput()?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'user_id')->dropDownList($user_list)?>		
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'subject')->textInput()?>		
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'content')->textArea()?>		
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'type')->dropDownList($type_list, ['multiple'=>'multiple']  )?>		
	</div>
	<div class="col-md-12">
		<?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
		
	</div>
</div>
<?php ActiveForm::end(); ?>

<?php
Modal::begin([
    'toggleButton' => [
        'label' => '<i class="glyphicon glyphicon-plus"></i> Add',
        'class' => 'btn btn-success'
    ],
    'closeButton' => [
      'label' => 'Close',
      'class' => 'btn btn-danger btn-sm pull-right',
    ],
    'size' => 'modal-lg',
]);
$myModel = new \app\models\Event;
echo $this->render('/events', ['model' => $myModel]);
Modal::end();
?>