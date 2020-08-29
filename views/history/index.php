<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История';
$this->params['breadcrumbs'][] = $this->title;

function actionTitle() {
    $arr = [
        'user/create' => 'Новый пользователь',
        'user/update' => 'Редактирование пользователя',
        'user/delete' => 'Удаление пользователя',

        'mail/subscribe' => 'Запуск рассылки',
        'mail/stop' => 'Остановлена рассылка',
        'mail/delete' => 'Удалена рассылка',
        'mail/update' => 'Редактирование рассылки',
        'mail/create' => 'Создана рассылка',
    ];

    return $arr;
}

?>
<div class="history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?/*= Html::a('Create History', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'user_id',
            [
                'attribute' => 'user_id',
                'value' => function($data){
                    $user = (new \app\models\User())->findUserById($data->user_id);
                    return $user->email.' ('.$user->username.')';
                },
                'format' => 'raw'
            ],

            [
                'attribute' => 'data',
                'value' => function($data){
                    return \app\controllers\HistoryController::formatHistory($data->data);
                    /*$arr = unserialize($data->data);
                    $text = '';
                    if(is_array($arr)){
                        foreach($arr as $elem => $a){
                            $text .= '<strong>'.$elem.':</strong> <span class="text-primary">'.$a[0].'</span> => <span class="text-danger">'.$a[1].'</span><br>';
                        }
                    }
                    return $text;*/
                },
                'format' => 'raw'
            ],

            [
                'attribute' => 'action',
                'value' => function($data){
                    return array_key_exists ($data->action, actionTitle()) ? actionTitle()[$data->action] : $data->action;
                },
                'format' => 'raw'
            ],
            'date',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
