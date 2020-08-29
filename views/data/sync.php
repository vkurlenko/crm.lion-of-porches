<?php

//use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\AppController as App;

/* @var $this yii\web\View */
/* @var $model app\models\Data */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .load-btn{
        padding: 20px 40px;
        margin: 20px;
    }
</style>

<div class="alert alert-info" style="margin: auto; width: 500px">
    <div class="data-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'sync')->fileInput() ?>

        <div class="form-group" style="margin: auto; text-align: center">
            <?= Html::submitButton('Загрузить файл', ['class' => 'btn btn-success load-btn']) ?>
            <br>
            <div class="alert alert-warning" style="margin-top: 20px">Изменения будут сохранены автоматически после загрузки файла</div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<br>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <?php
        if($updatelog) {
            echo $updatelog;
        }
        ?>
    </div>
</div>
