<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "<!--{label}\n--><div class=\"col-md-offset-4 col-md-3\">{input}</div>\n<div class=\"col-md-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-4 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-md-offset-4 col-md-3\">{input} {label}</div>\n<div class=\"col-md-5\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-md-offset-4 col-md-8">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <!--<div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>-->
</div>
