<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('form','Registration');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?=Yii::t('form','Signup appeal')?></p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'signup-form', 'options' => ['autocomplete' => 'off']]); ?>

                <?= $form->field($model, 'username', ['inputOptions' => ['autocomplete' => 'off']])->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email', ['inputOptions' => ['autocomplete' => 'off']]) ?>

                <?= $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'off']])->passwordInput() ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('form','Submit'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>