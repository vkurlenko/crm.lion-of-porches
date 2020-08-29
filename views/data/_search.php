<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?/*= $form->field($model, 'id') */?><!--

    <?/*= $form->field($model, 'code') */?>

    --><?/*= $form->field($model, 'name') */?>

    <?/*= $form->field($model, 'discount') */?>


    <table class="search-table">
        <tr>
            <td><?php echo $form->field($model, 'card') ?></td>
            <td><?php echo $form->field($model, 'user_name') ?></td>
            <td><?php echo $form->field($model, 'phone') ?></td>
        </tr>

        <tr>
            <td>
                <div class="form-group">
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('Сброс', ['class' => 'btn btn-default', 'id' => 'search-reset']) ?>
                </div>
            </td>
        </tr>
    </table>



    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'born_year') ?>

    <?php // echo $form->field($model, 'born_date') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'subscribe') ?>



    <?php ActiveForm::end(); ?>

</div>
