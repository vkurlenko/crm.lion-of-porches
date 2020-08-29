<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $model app\models\Mail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject')->textInput() ?>

    <?= $form->field($model, 'body')
        ->widget(CKEditor::className(), [
                'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                        //'options' => ['rows' => 100, 'minHeight' => 500,],
                        //'rows' => 100,
                        'height' => 500,
                        //'width' => 800,
                        'preset' => 'advanced',
                        //'template' => '12345',
                    ]
                )

        ]

    ); ?>
<!--
    --><?/*= $form->field($model, 'image')->textarea(['rows' => 6]) */?>



    <?php if($model->mailImage): ?>
        <img src="/<?= $model->mailImage?>" alt="">
    <?php endif; ?>

    <?= $form->field($model, 'mailImage')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
