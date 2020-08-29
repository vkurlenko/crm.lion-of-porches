<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новый пользователь', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'username',
                'value' => function($data){
                    return Html::a($data->username, ['user/update/?id='.$data->id]);
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'role.item_name',
                'label' => 'Роль',
                'value' => function($data){
                    return \app\controllers\AppController::renderRole($data->role);
                },
                'format' => 'html'
            ],
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email',
            [
                'attribute' => 'created_at',
                'value' => function($data){
                    return date('d.m.Y H:i', $data->created_at);
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($data){
                    return date('d.m.Y H:i', $data->updated_at);
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'status',
                'value' => function($data){
                    $status = '';
                    switch ($data->status){
                        case 0: $status = 'отключен';
                            break;
                        case 1: $status = 'активен';
                            break;
                        case 5: $status = 'не подтвержден';
                            break;
                    }

                    return $status;
                },
                'format' => 'html'
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
