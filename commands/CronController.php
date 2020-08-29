<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12.09.2018
 * Time: 22:13
 */

namespace app\commands;

use yii\console\Controller;
use app\controllers\MailController;
use yii\console\ExitCode;

class CronController extends Controller
{
    public function actionIndex()
    {
        //fopen('test.txt', 'a+');
        MailController::actionSend();

        return ExitCode::OK;
    }
}