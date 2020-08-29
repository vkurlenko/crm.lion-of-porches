<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mail */

$this->title = 'Изменить письмо: ' . $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Рассылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subject, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Сохранить';
?>
<div class="mail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
