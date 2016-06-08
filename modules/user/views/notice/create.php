<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
?>
<h1><?=Yii::t('notice', 'Create')?></h1>
<div class="row">
<?php $form = ActiveForm::begin(); ?>
	<div class="col-md-6">
		<?= $form->field($model, 'title')->textInput()?>		
	</div>
	<div class="col-md-6">
	<?php
	echo $form->field($model, 'event_id')->textInput(['readonly'=>'true']);
	Modal::begin([
	    'toggleButton' => [
	        'label' => '<i class="glyphicon glyphicon-new-window"></i> ' . Yii::t('notice', 'Event choose'),
	        'class' => 'btn btn-variant'
	    ],
	    'closeButton' => [
	      'label' => 'Close',
	      'class' => 'btn btn-danger btn-sm pull-right',
	    ],
	    'size' => 'modal-lg',
	]);
	$events = \app\models\Event::getAll();
	$actionColumnParams = [
    	'class' => 'yii\grid\ActionColumn',
    	'header' => Yii::t('users', 'Actions'),
    	'template' => '{choose}',
    	'buttons' => [	            	
        	'choose' => function ($url, $model, $key) {
        		return Html::button('', [
        			'class' => 'glyphicon glyphicon-ok-sign',
        			'title' => Yii::t('events', 'Choose'),
        			'onclick' => '(function ( btn ) {
        				console.log($(btn).parent().parent().data("key"));
        				var $tr = $(btn).parent().parent();
        				$("#notice-event_id").val($tr.data("key"));
        				var type = $tr.find("td:eq(5)").html();
        				$(".input-helper").hide();
        				if (type == "1" || type == "3")
							$(".user-params").show();
						else
							$(".article-params").show();
        				$("button.btn.btn-danger.btn-sm").click();

        			})(this);'
        		]);
                return Html::a('<span class="glyphicon glyphicon-ok-sign"></span>', $url, [
                        'title' => Yii::t('events', 'Choose'),
                ]);
            }
    	],
    ];
	echo $this->render('/events/index', ['model' => $events, 'actionColumnParams' => $actionColumnParams]);
	Modal::end();
	?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'user_id')->dropDownList($user_list)?>		
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'subject')->textInput()?>		
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'content')->textArea(['rows' => 7])?>
		<span id="article_params_helper" style="color:#999;">
	        You may insert params 
	        <span class="input-helper article-params">
	        	<strong>{articlename}</strong> or <strong>{articlelink}</strong>
	        </span>
	        <span class="input-helper user-params" style="display: none">
	        	<strong>{username}</strong> or <strong>{userid}</strong>
	        </span>
	    </span>
	    <br><br>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'type')->dropDownList($type_list, ['multiple'=>'multiple']  )?>		
	</div>
	<div class="col-md-12">
		<?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
		
	</div>
<?php ActiveForm::end(); ?>
</div>

