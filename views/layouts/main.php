<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->params['companyName'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $nav_widget_items = [
        ['label' => 'Home', 'url' => ['/site/index']],
        // ['label' => 'About', 'url' => ['/site/about']],
        // ['label' => 'Contact', 'url' => ['/site/contact']],
        ['label' => 'Notice', 'url' => ['/site/notice']],        
    ];
    if (Yii::$app->user->can('admin')) {       
        // $nav_widget_items[] = ['label' => 'Admin', 'url' => ['/site/admin']];
        $nav_widget_items[] = ['label' => 'Admin', 'items' => [
            ['label' => 'Users', 'url' => ['/admin/users/']],
            ['label' => 'Articles', 'url' => ['/admin/articles']],
            ['label' => 'Notice', 'url' => ['/admin/notice']],
            ['label' => 'Events', 'url' => ['/admin/events']],
        ]]; 
    }

    if (Yii::$app->user->isGuest)
        $nav_widget_items[] = ['label' => 'Login', 'url' => ['/site/login']];
    else
        $nav_widget_items[] = 
            '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $nav_widget_items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?=Yii::$app->params['companyName']?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
