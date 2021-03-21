<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
//use Da\QrCode\QrCode;

/* @var $this yii\web\View */
/* @var $model app\models\Data */

$this->title = $model->user_name;
$this->params['breadcrumbs'][] = ['label' => 'Все записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-view container">

    <h1><?= Html::encode($this->title) ?><?= Html::img('/data/qr?string='.$model->card, ['align' => 'right']);?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>

        <?php
            if(trim($model->email) != '') :
        ?>
        <?= Html::a('Отправить QR-код на email', ['sendqrcode', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?php
            endif;
        ?>

    </p>

    <?php /*foreach(Yii::$app->session->getAllFlashes() as $type => $messages): */?><!--
        <?php /*foreach($messages as $message): */?>
            <div class="alert alert-<?/*= $type */?>" role="alert"><?/*= $message */?></div>
        <?php /*endforeach */?>
    --><?php /*endforeach */?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            /*[
                'attribute' => 'code',
                'value' => function($data){
                    return $data->code ? sprintf("%'09d\n", $data->code) : '';
                }
            ],
            [
                'attribute' => 'name',
                'value' => function($data){
                    return $data->name ? sprintf("%'012d\n", $data->name) : '';
                }
            ],*/
            [
                'attribute' => 'card',
                'value' => function($data){
                    return $data->card ? sprintf("%'05d\n", $data->card) : '';
                }
            ],
            //'discount',
            [
                'attribute' => 'discount',
                'value' => function($data){
                    return $data->discount.'%';
                }
            ],
            [
                'attribute' => 'sprdiscount',
                'value' => function($data){
                    return $data->sprdiscount;
                }
            ],
            [
                'attribute' => 'summapokupok',
                'value' => function($data){
                    return $data->summapokupok;
                }
            ],
            [
                'attribute' => 'vid_card',
                'value' => function($data){
                    return $data->vid_card;
                }
            ],
            [
                'attribute' => 'activation_date',
                'value' => function($data){
                    //$month =  intval(date("m", strtotime($data->activation_date)));
                    return $data->activation_date != '0000-00-00' ? date("d.m.Y", strtotime($data->activation_date)) : '';
                }
            ],
            'user_name:ntext',
            [
                'attribute' => 'gender',
                'value' => function($data){
                    return $data->gender ? 'мужской' : 'женский';
                }
            ],
            [
                'attribute' => 'age',
                'value' => function($data){
                    return $data->born_date != '0000-00-00' ? \app\controllers\DataController::getAge($data->born_date) : '';
                }
            ],
            [
                'attribute' => 'born_year',
                'value' => function($data){
                    return $data->born_date != '0000-00-00' ? date("Y", strtotime($data->born_date)) : '';
                }
            ],
            [
                'attribute' => 'born_date',
                'value' => function($data){
                    //$month =  intval(date("m", strtotime($data->born_date)));
                    return $data->born_date != '0000-00-00' ? date("d.m.Y", strtotime($data->born_date)) : '';
                },
            ],
            'email:email',
            'phone',
            'comment:ntext',
            [
                'attribute' => 'subscribe',
                'value' => function($data){
                    return $data->subscribe ? 'Да' : 'Нет';
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'sms',
                'value' => function($data){
                    return $data->sms ? 'Да' : 'Нет';
                },
                'format' => 'raw'
            ],
        ],
    ]) ?>

</div>
