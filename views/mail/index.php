<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рассылки';
$this->params['breadcrumbs'][] = $this->title;

$res = \app\controllers\MailController::getSubscribeStatus();

?>
<div class="mail-index">

    <div>
        <?php
        if(!empty($res)){
            ?>
            Всего подписчиков <span class="badge"><?=$res['all']?></span>
            Отправлено <span class="badge badge-success"><?=$res['status1']?></span>
            Не отправлено <span class="badge badge-primary"><?=$res['status0']?></span>
            Ошибка email <span class="badge badge-danger"><?=$res['status2']?></span>
            Email не указан <span class="badge badge-danger"><?=$res['status3']?></span>
            <?php
        }
        ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новое письмо', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Продолжить рассылку', ['/mail/send'], ['class' => 'btn btn-success']) ?>
        <? if(\app\controllers\MailController::isActiveSubscribe())
            echo Html::a('Остановить рассылку', ['/mail/stop'], ['class' => 'btn btn-danger', 'style' => 'float:right'])
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'subject:ntext',
            //'active',
            [
                'attribute' => 'active',
                'value' => function($data){
                    return $data->active ? '<span class="green"><strong>Активная рассылка</strong></span>' : '<span class="red"></span>';
                },
                'format' => 'raw'
            ],
            //'body:ntext',
            //'mailImage:ntext',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {/mail/subscribe} {/mail/test}',
                'buttons' => [
                    '/mail/subscribe' => function ($url) {
                        return Html::a('Запустить рассылку', $url, ['class' => 'btn btn-success']);
                    },
                    '/mail/test' => function ($url) {
                        return Html::a('Тест', $url, ['class' => 'btn']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
