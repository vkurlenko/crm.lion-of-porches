<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Data */

$this->title = 'Изменить запись: ' . $model->user_name;
$this->params['breadcrumbs'][] = ['label' => 'Все записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="data-update container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
