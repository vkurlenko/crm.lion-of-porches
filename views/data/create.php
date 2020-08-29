<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Data */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-create container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
