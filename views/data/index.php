<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Записи';
$this->params['breadcrumbs'][] = $this->title;

if (!\Yii::$app->user->can('manager')){
    $template = '{view}';
} else {
    $template = '{view} {update} {delete}';
}
?>
<div class="data-index">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    //\app\controllers\DataController::convertDate('born_date');
    //\app\controllers\DataController::convertGender();
    //\app\controllers\DataController::setCardNumber();
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
           /* [
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
            ['class' => 'yii\grid\ActionColumn', 'template' => $template],
            [
                'attribute' => 'card',
                'value' => function($data){
                    $card_number = $data->card ? sprintf("%'013d\n", $data->card) : '';

                    return sprintf('<a href="/data/update?id=%s">%s</a>', $data->id, $card_number);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'activation_date',
                'value' => $data->activation_date,
                /*'content'=>function($data){
                    return $data->activation_date == '0000-00-00' ? '0000-00-00' : $data->activation_date;
                },*/
                'format' => ['date', 'dd.MM.yyyy'],//'format' => 'raw',
                'label' => 'Дата активации'
            ],
            [
                'attribute' => 'discount',
                'value' => function($data){
                    return $data->discount ? $data->discount : '';
                }
            ],
            [
                'attribute' => 'sprdiscount',
                'value' => function($data){
                    return $data->sprdiscount ? $data->sprdiscount : '';
                }
            ],
            [
                'attribute' => 'summapokupok',
                'value' => function($data){
                    return $data->summapokupok ? $data->summapokupok : '';
                }
            ],
            [
                'attribute' => 'vid_card',
                'value' => function($data){
                    return $data->vid_card ? $data->vid_card : '';
                }
            ],
            [
                'attribute' => 'user_name',
                'value' => function($data){
                    $arr = explode(' ', $data->user_name);

                    foreach ($arr as $k => &$elem) {
                        $elem = trim($elem);

                        if ($elem == '') {
                            unset($arr[$k]);
                        }
                    }

                    return $data->user_name ? implode('<br>', $arr) : '';
                },
                'format' => 'raw'
            ],
            //'user_name:ntext',
            //'gender',
            [
                'attribute' => 'gender',
                'value' => function($data){
                    return $data->gender ? 'м' : 'ж';
                },
                'filter'=>array("1"=>"М","0"=>"Ж"),
            ],
            //'age',
            /*[
                'attribute' => 'age',
                'value' => function($data){
                    return $data->born_date != '0000-00-00' ? \app\controllers\DataController::getAge($data->born_date) : '';
                }
            ],*/
            [
                'attribute' => 'born_date',
                'value' => $data->born_date,
                'content'=>function($data){
                    return $data->born_date != '0000-00-00' ? \app\controllers\DataController::getAge($data->born_date) : '';
                },
                'format' => ['date', 'dd.MM.yyyy'],//'format' => 'raw',
                'label' => 'Возраст'
            ],
            //'born_year',
            [
                'attribute' => 'born_date',
                'value' => $data->born_date,
                'content'=>function($data){
                    return $data->born_date != '0000-00-00' ? date("Y", strtotime($data->born_date)) : '';
                },
                'format' => ['date', 'dd.MM.yyyy'],//'format' => 'raw',
                'label' => 'Год рождения'
            ],


            //'born_date',
            /*[
                'attribute' => 'born_date',
                'value' => function($data){
                    $month =  intval(date("m", strtotime($data->born_date)));
                    return $data->born_date != '0000-00-00' ? '<span class="m" data-month="m'.$month.'">'.date("d.m.Y", strtotime($data->born_date)).'</span>' : '';
                    //return $data->born_date != '0000-00-00' ? '<span class="m m'.$month.'">'.$data->born_date.'</span>' : '';
                },
                'format' => 'raw'
            ],*/

            [
                'attribute' => 'born_date',
                'value' => $data->born_date,
                'content'=>function($data){
                    $month =  intval(date("m", strtotime($data->born_date)));
                    return $data->born_date != '0000-00-00' ? '<span class="m" data-month="m'.$month.'">'.date("d.m.Y", strtotime($data->born_date)).'</span>' : '';
                },
                'format' => ['date', 'dd.MM.yyyy']//'raw'
            ],

            'email:email',
            'phone',
            'comment:ntext',
            //'subscribe',
            [
                'attribute' => 'subscribe',
                'value' => function($data){
                    return $data->subscribe ? '<span class="green">Да</span>' : '<span class="red">Нет</span>';
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'sms',
                'value' => function($data){
                    return $data->sms ? '<span class="green">Да</span>' : '<span class="red">Нет</span>';
                },
                'format' => 'raw'
            ],
            //'send_status',
            [
                'attribute' => 'send_status',
                'value' => function($data){
                    switch ($data->send_status){
                        case 0 : $str = ''; break;
                        case 1 : $str = '<span class="green">Отправлено</span>'; break;
                        case 2 : $str = '<span class="red">Ошибка email</span>'; break;

                    }
                    return $str; //$data->send_status ? '<span class="green">Ok</span>' : '<span class="red">Нет</span>';
                },
                'format' => 'raw'
            ],

            //['class' => 'yii\grid\ActionColumn', 'template' => $template],
        ],
    ]); ?>
</div>
<!--
<?php
//phpinfo();
?>
-->
