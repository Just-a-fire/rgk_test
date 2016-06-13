<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните эти поля:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Логин') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11" style="margin-bottom: 20px;">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button', 'style' => 'min-width: 103px;']) ?>
                <?= Html::a('Забыли пароль?', array('/site/forgot'), array('style' => 'display: none; margin-left: 10px;')); // inline-block ?>
            </div>
            <div class="col-lg-offset-1 col-lg-11">
                Новый аккаунт?
                <?= Html::a('Регистрация', array('/site/signup'), array('style' => 'display: inline-block; margin-left: 10px;')); ?>                
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
