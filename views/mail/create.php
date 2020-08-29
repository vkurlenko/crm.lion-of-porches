<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mail */

$this->title = 'Новое письмо';
$this->params['breadcrumbs'][] = ['label' => 'Рассылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
