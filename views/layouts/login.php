<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">


    <div class="container container-login">

        <div class="row">
            <div class="container-fluid">

                <div class="col-md-10 login-header">
                    <?= Html::img('/web/tpl/vincinelli.gif')?> <span class="app-title">/ Customer relationship managment</span>
                </div>
                <div class="col-md-2">
                    <?= Html::a(Html::img('/tpl/logo.png', ['align' => 'right']), '/');?>
                </div>


            </div>
        </div>

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
