<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\controllers\AppController as App;
use app\controllers\UserController as User;
use app\models\AuthAssignment as Auth;

AppAsset::register($this);

$user_role =  (new Auth())->getUserRole(Yii::$app->user->identity->id);
$user_role_name = (new \app\models\AuthItem())->getRoleName($user_role);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/favicon.png">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '', //Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

   $admin_or_manager = App::isAdmin() || App::isManager();


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            //['label' => 'Главная', 'url' => ['/']],
            ['label' => 'Покупатели', 'url' => ['/data']],

            // для админа и менеджера
            $admin_or_manager ? (
            ['label' => 'Рассылки', 'url' => ['/mail']]
            ) : '',

            // только для админа
            App::isAdmin() ? (
                ['label' => 'Пользователи', 'url' => ['/user']]
            ) : '',
            App::isAdmin() ? (
                ['label' => 'История', 'url' => ['/history']]
            ) : '',


            Yii::$app->user->isGuest ? (
                ['label' => 'Войти', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ' <em>'.$user_role_name.'</em>)',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <p>
                    <?= Html::a('Внести покупателя', ['/data/create'], ['class' => 'btn  btn-lip']) ?>

                    <?if($admin_or_manager) :?>
                    <?= Html::a('Сделать рассылку', ['/mail/create'], ['class' => 'btn  btn-lip']) ?>
                    <?endif?>

                    <?if(App::isAdmin()) :?>
                        <?= Html::a('Синхронизация с 1С', ['/data/sync'], ['class' => 'btn  btn-lip']) ?>
                    <?endif?>
                </p>
            </div>
            <div class="col-md-2">
                <?= Html::a(Html::img('/tpl/logo.png', ['align' => 'right']), '/');?>
            </div>

        </div>
    </div>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <!--<p class="pull-left">&copy; My Company <?/*= date('Y') */?></p>

        <p class="pull-right"><?/*= Yii::powered() */?></p>-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
