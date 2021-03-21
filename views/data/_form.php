<?php

//use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\AppController as App;

/* @var $this yii\web\View */
/* @var $model app\models\Data */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?/*= $form->field($model, 'code')->textInput() */?><!--

    --><?/*= $form->field($model, 'name')->textInput() */?>

    <?= $form->field($model, 'card')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'activation_date')->widget(\yii\jui\DatePicker::class, ['language' => 'ru', 'dateFormat' => 'yyyy-MM-dd']) ?>

    <?= $form->field($model, 'discount')->textInput(['readonly' => !App::isAdmin()]) ?>

    <?= $form->field($model, 'sprdiscount')->textInput(['value' => $model->isNewRecord ? 'Накопительные скидки' : $model->sprdiscount]) ?>

    <?= $form->field($model, 'summapokupok')->textInput() ?>

    <?= $form->field($model, 'vid_card')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->radioList(['женский', 'мужской']);//dropDownList([ '0' => 'женский', '1' => 'мужской', ], ['prompt' => 'Выберите пол']) ?>

    <?/*= $form->field($model, 'age')->textInput() */?><!--

    --><?/*= $form->field($model, 'born_year')->textInput() */?>

    <?= $form->field($model, 'born_date')->widget(\yii\jui\DatePicker::class, ['language' => 'ru', 'dateFormat' => 'yyyy-MM-dd']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'subscribe')->checkbox([1, 0]) ?>
    <?= $form->field($model, 'sms')->checkbox([1, 0]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
