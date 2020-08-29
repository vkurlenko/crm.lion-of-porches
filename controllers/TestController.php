<?php

namespace app\controllers;

use app\models\Data;
use Yii;
use yii\web\Controller;
use yii\validators\EmailValidator;

/* проверка выборки дней рождения */
class TestController extends AppController
{
    /**
     * отправка писем-напоминаний о скидке к ДР
     *
     */
    public function actionIndex()
    {
        //fopen('reminder.txt', 'a+');

        $arr = self::getUsers();

        debug($arr);

        foreach( $arr as $interval => $users ){
            foreach( $users as $user ){

                // сформируем письмо
                $subject = 'Вам предоставлена скидка 20% в магазинах Lion Of Porches в честь Дня рождения!';
                $params = self::getMailFields($interval, $user);

                $message = Yii::$app->mailer->compose('mail-remind', $params);

                $validator = new EmailValidator();
                if ( $validator->validate( $user['email'], $error )) {
                    $send = $message
                        //->setTo($user['email'])
                        ->setTo('vkurlenko@mail.ru')
                        //->setFrom('lofporches@yandex.ru')
                        ->setFrom(['loyalty@lion-of-porches.ru' => 'Lion Of Porches'])
                        ->setSubject($subject)
                        ->send();
                }
                else{
                    $send = false;
                }
            }
        }

        return $send;//ExitCode::OK;
    }

    /**
     * сформируем поля письма {имя, дата ДР, дата окончания скидки}
     */
    public static function getMailFields( $interval = null, $user = [] ){

        if($interval != null && !empty($user)){

            //$date_stop = date_create($user['born_date']);

            if($interval == 'today')
                $date_stop  = mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"));
            //date_add($date_stop, date_interval_create_from_date_string('7 days'));
            else
                $date_stop  = mktime(0, 0, 0, date("m")  , date("d")+14, date("Y"));
            //date_add($date_stop, date_interval_create_from_date_string('14 days'));

            $params = [
                'user_name'  => $user['user_name'],
                'born_date'  => $user['born_date'],
                //'date_stop'  => date_format($date_stop, 'Y-m-d')
                'date_stop'  => date('d.m.Y', $date_stop),
                'image'     => dirname(__DIR__).'/web/images/card.jpg'
            ];

            //print_r($params);

            return $params;
        }

        return false;

    }

    /**
     * получим записи клиентов у кого ДР сегодня и через 7 дней
     */
    public static function getUsers()
    {
        $arr = [];

        // выберем клиентов с ДР сегодня
        $users_today = Data::find()
            //->where(['born_date' => date('Y-m-d')])
            ->where(['like', 'born_date', '%-'.date('m-d'), false])
            ->asArray()
            ->all();

        $arr['today'] = $users_today;




        // выберем клиентов с ДР через 7 дней
        $week = date_create(date('Y-m-d'));

        date_add($week, date_interval_create_from_date_string('7 days'));

        echo date_format($week, 'm-d');

        $users_week = Data::find()
            //->where(['born_date' => date_format($week, 'Y-m-d')])
            ->where(['like', 'born_date', '%-'.date_format($week, 'm-d'), false])
            ->asArray()
            ->all();

        $arr['week'] = $users_week;

        return $arr;
    }
}