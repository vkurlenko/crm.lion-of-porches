<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 24.09.2018
 * Time: 12:39
 */

namespace app\controllers;


use yii\console\Controller;
use Passbook\Pass\Barcode;
use Passbook\Pass\Field;
use Passbook\Pass\Image;
use Passbook\Pass\Structure;
use Passbook\PassFactory;
use Passbook\PassValidator;
use Passbook\Type\EventTicket;

class PassController extends Controller
{
    public function actionIndex(){
        return $this->render('index');
    }

    public function actionPass(){

        // каждый пасс должен иметь уникальный идентификатор
        $date = new \DateTime();
        $uniqueId = 'best_pass_ever_' . $date->format('Y_m_d_h_i_s');
        $pass = new EventTicket($uniqueId, 'Wallet example');
        // устанавливаем цвет бэкграунда
        $pass->setBackgroundColor('rgb(255, 255, 255)');
        // устанавливаем цвет шрифта
        $pass->setForegroundColor('rgb(0, 0, 0)');
        // устанавливаем цвет шрифта лейблов
        $pass->setLabelColor('rgb(0, 0, 0)');

        $structure = new Structure();

        // поле заголовка
        // конструктор поля должен состоять из уникального ключа и значения поля
        $headerField = new Field('header', 'Поле заголовка');
        // также можно добавить лейбл
        $headerField->setLabel('Лейбл поля заголовка');
        $structure->addHeaderField($headerField);

        // вторичное поле
        $secondaryField = new Field('secondary', 'Вторичное поле');
        $secondaryField->setLabel('Лейбл вторичного поля');
        $structure->addSecondaryField($secondaryField);

        // дополнительное поле
        $auxiliaryField = new Field('auxiliary', 'Дополнительное поле');
        $auxiliaryField->setLabel('Лейбл дополнительного поля');
        $structure->addAuxiliaryField($auxiliaryField);

        // первое поле задней стороны пасса
        $backField = new Field('back_field_one', '+7 (999) 999-99-99');
        $backField->setLabel('Пример с номером телефона:');
        $structure->addBackField($backField);

        // второе поле задней стороны пасса
        $backField = new Field('back_field_two', '<a href="https://google.com">Ссылка</a>');
        $backField->setLabel('Пример ссылки:');
        $structure->addBackField($backField);

        // можно не ограничиваться одним или двумя!

        // логотип
        //echo dirname('./passes/sample/'); die;
        $logoImage = new Image('./passes/sample2/logo.png', 'logo');
        $pass->addImage($logoImage);
        $logoImage = new Image('./passes/sample2/logo@2x.png', 'logo');
        $logoImage->setIsRetina(true);
        $pass->addImage($logoImage);

        // иконка, видна на экране блокировки
        $iconImage = new Image('./passes/sample2/icon.png', 'icon');
        $pass->addImage($iconImage);
        $iconImage = new Image('./passes/sample2/icon@2x.png', 'icon');
        $iconImage->setIsRetina(true);
        $pass->addImage($iconImage);

        // strip
        /*$stripImage = new Image('./passes/sample2/', 'strip');
        $pass->addImage($stripImage);
        $stripImage = new Image('./passes/sample2/', 'strip');
        $stripImage->setIsRetina(true);
        $pass->addImage($stripImage);*/

        $pass->addAssociatedStoreIdentifier(544007664);

        $pass->setStructure($structure);

        $barcode = new Barcode(Barcode::TYPE_QR, 'http://google.com');
        $pass->setBarcode($barcode);

        $factory = new PassFactory(
        // Идентификатор типа пасса (bundle id)
            'TYPE_IDENTIFIER',
            // Идентификатор команды разработчика
            'TEAM_ID',
            // Название компании разработчика
            'ORGANIZATION_NAME',
            // Путь до сертификата разработчика
            './passes/sample2/cert/dummy.p12',
            // Пароль к сертификату разработчика
            './passes/sample2/cert/signature',
            // Путь до WWDR сертификата
            './passes/sample2/cert/dummy.wwdr'
        );

        $factory->setOverwrite(true);
        // Путь до папки, в которую будут сохраняться готовые пассы
        $factory->setOutputPath('./passes/sample2/pass/');

        $file = $factory->package($pass);

        $validator = new PassValidator();
        $validator->validate($pass);
        if ($validator->getErrors()) {
            $errorsString = implode(' | ', $validator->getErrors());
            throw new \Exception("Ошибки валидации passbook: $errorsString");
        }

        $passBookFullPath = $file->getPath() . "/{$uniqueId}.pkpass";

        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/vnd.apple.pkpass");
        header('Content-Disposition: attachment; filename="pass.pkpass"');
        clearstatcache();
        $fileSize = filesize($passBookFullPath);
        if ($fileSize) {
            header("Content-Length: " . $fileSize);
        }
        header('Content-Transfer-Encoding: binary');
        if (filemtime($passBookFullPath)) {
            date_default_timezone_set("UTC");
            header(
                'Last-Modified: '
                . date("D, d M Y H:i:s", filemtime($passBookFullPath))
                . ' GMT'
            );
        }
        flush();
        readfile($passBookFullPath);
        exit();
    }
}