<?php

namespace app\controllers;

use moonland\phpexcel\Excel;
use Yii;
use app\models\Data;
use app\models\DataSearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
//use Da\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use yii\filters\AccessControl;
use app\models\History;
use yii\web\UploadedFile;
use app\controllers\AppController;

/**
 * DataController implements the CRUD actions for Data model.
 */
class DataController extends AppController
{
    public $RESULT = [
        '', //'не обновлена',
        'обновлена',
        'не найдена'
    ];

    public $LOYALITY = [
        '5' => [
            'title' => 'BASIC',
            'sum'   => 10000,
            'img'   => 'basic.jpg'],
        '10' => [
            'title' => 'SILVER',
            'sum'   => 70000,
            'img'   => 'silver.jpg'],
        '15' => [
            'title' => 'GOLD',
            'sum'   => 150000,
            'img'   => 'gold.jpg'],
        '20' => [
            'title' => 'PLATINUM',
            'sum'   => 250000,
            'img'   => 'platinum.jpg'],
        '25' => [
            'title' => 'SIGNATURE',
            'sum'   => 350000,
            'img'   => 'signature.jpg'],
        '30' => [
            'title' => 'ULTRA',
            'sum'   => 500000,
            'img'   => 'ultra.jpg'],
        '50' => [
            'title' => 'ULTRA',
            'sum'   => 500000,
            'img'   => 'ultra.jpg']
    ];

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                   /* [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['admin', 'manager'],
                    ],*/
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    /**
     * Lists all Data models.
     * @return mixed
     */
    public function actionIndex()
    {
        //debug(Yii::$aliases);
        $searchModel = new DataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Data model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Data model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Data();
        $oldModel = $model->attributes;

        $model->discount = 0;
        $model->activation_date = date('Y-m-d');

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {

            $check = self::check($model);

            if($check && $model->save()){
                $newModel = $model->attributes;
                (new History())->setRow(Yii::$app->controller, $oldModel, $newModel, 'Создан клиент '.$newModel['user_name']);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        else{
            // сформируем номер новой карты клиента
            $next_card = Data::find()->max('card') + 1;
            $model->card = $next_card;
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * проверка на создание дубликата записи по имени, email и телефону
     */
    public static function check($model)
    {
        $flag = true;
        $records = Data::find()->select('user_name, phone, email')->asArray()->all();

        foreach($records as $record){
            if(self::convertString($model->user_name) == self::convertString($record['user_name'])){
                Yii::$app->session->addFlash('danger', 'Клиент с именем <em><b>' . $record['user_name'] . '</b></em> уже существует');
                $flag = false;
                break;
            }

            if(self::convertString($model->phone) == self::convertString($record['phone'])){
                Yii::$app->session->addFlash('danger', 'Клиент с телефоном <em><b>' . $record['phone'] . '</b></em> уже существует');
                $flag = false;
                break;
            }

            /*if(self::convertString($model->email) == self::convertString($record['email'])){
                Yii::$app->session->addFlash('danger', 'Клиент с email <em><b>' . $record['email'] . '</b></em> уже существует');
                $flag = false;
                break;
            }*/
        }
        return $flag;
    }


    public static function convertString($string)
    {
        return str_replace(' ', '', mb_strtolower($string));
    }

    public static function setCardNumber()
    {
        $arr = Data::find()->where(['card' => 0])->asArray()->all();

        if(!empty($arr)){
            $max = Data::find()->max('card');
            $max++;
            echo $max;
            foreach($arr as $row){
                $q = Data::findOne($row['id']);
                $q->card = $max++;
                $q->save(false);
            }
        }

        //debug($arr);
    }

    /**
     * Updates an existing Data model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('manager')) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие. ');
        }

        $model = $this->findModel($id);
        $oldModel = $model->attributes;
        $model->send_status = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $newModel = $model->attributes;
            (new History())->setRow(Yii::$app->controller, $oldModel, $newModel, 'Отредактирован клиент '.$oldModel['user_name']);
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Data model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('manager')) {
            throw new ForbiddenHttpException('Вам не разрешено производить данное действие. ');
        }

        $oldModel = $this->findModel($id);
        $this->findModel($id)->delete();

        (new History())->setRow(Yii::$app->controller, $oldModel, [], 'Удален клиент '.$oldModel['user_name']);

        return $this->redirect(['index']);
    }

    // генерация QR-кода номера карты
    public function actionQr($string = null)
    {
        $string = Yii::$app->request->get('string');

        $str = sprintf("%'013d\n", $string); // . ' Скидка 50%';

        $qrCode = new QrCode($str);

        //$qrCode->setWriterByName('png');
        $qrCode->setSize(252);
        $qrCode->setMargin(10);
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setValidateResult(false);
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);

        $qrCode->writeFile('./qr/qrcode.png');

        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    }

    // отправка QR-кода номера карты на mail клиента
    public function actionSendqrcode($id = null)
    {
        $send = false;
        $subject = 'Ваш уникальный QR-код для участия в программе лояльности Lion Of Porches';

        if(Yii::$app->request->get('id'))
            $id = Yii::$app->request->get('id');

        if($id){
            $user = self::findModel($id);

            if($user){
                if($user->email){
                    $params = [
                        'user_name' => $user->user_name,
                        'card' => $user->card,
                        'imageFileName' => './qr/qrcode.png'
                    ];

                    $message = Yii::$app->mailer->compose('mail-html', $params);

                    //$message->attach('/path/to/source/file.pdf');

                    $send = $message
                        ->setTo($user->email)
                        ->setBcc('vkurlenko@yandex.ru')
                        //->setFrom('lofporches@yandex.ru')
                        ->setFrom(['loyalty@lion-of-porches.ru' => 'Lion Of Porches'])
                        ->setSubject($subject)
                        //->setTextBody('Текст сообщения')
                        //->attach('./passes/sample.pkpass')
                        ->send();
                }
            }
        }

        if($send) {
            Yii::$app->session->addFlash('success', 'QR-код отправлен');
            (new History())->setRow(Yii::$app->controller, $user, [], 'Отправлен QR-код клиенту '.$user['user_name']);
        } else {
            Yii::$app->session->addFlash('danger', 'Ошибка отправки QR-кода');
            (new History())->setRow(Yii::$app->controller, $user, [], 'Ошибка отправки QR-кода клиенту '.$user['user_name']);
        }


        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Синхронизация с 1С
     *
     * @return string
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionSync()
    {
        $model = new Data();

        // номер первой строки данных в файле excel
        $startIndex = 2;

        $importOptions = [
            'setFirstRecordAsKeys' => false,
            'setIndexSheetByName' => false,
            'getOnlySheet' => 'sheet1',
        ];
        $log = [];
        $historyOld = [];
        $historyNew = [];
        $updatelog = '';

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'sync');

            if(!empty($file)) {
                $path = $this->getImportPath($file);
                $fullPath = $this->getFullPath($path);

                $saveFile = $file->saveAs($path);

                if($saveFile) {
                    Yii::$app->session->addFlash('success', 'Файл '.$file->baseName . '.' . $file->extension.' загружен');

                    $data = Excel::import($fullPath, $importOptions);

                    foreach((array)$data as $index => $row) {
                        if($index < $startIndex) {
                            continue;
                        }
                        /*
                         * [10] => Array
                            (
                                [A] =>
                                [B] => Rui Maia
                                [C] => 50
                                [D] => 0000000052942
                                [E] => 03.12.1961 0:00:00
                                [F] => 2,000,000.00
                                [G] => VIP
                            )*/

                        foreach($row as $column => $value) {
                            switch ($column) {
                                case 'A':
                                    break;
                                case 'B':
                                    $user_name = $value;
                                    break;
                                case 'C':
                                    $discount = $value;
                                    break;
                                case 'D':
                                    $card = $value;
                                    break;
                                case 'E':
                                    break;
                                case 'F':
                                    break;
                                case 'G':
                                    $is_vip = trim(strtoupper($value)) == 'VIP' ? true : false;
                                    break;
                            }
                        }

                        $log[+$card] = [
                            'id' => 0,
                            'card' => sprintf("%'013d\n", $card),
                            'user_name' => '',
                            'discount_old' => '',
                            'discount_new' => '',
                            'vip' => false,
                            'result' => 2
                        ];

                        $record = (new Data())->findByCard(+$card);

                        if($record){

                            $log[+$record->card] = [
                                'id' => $record->id,
                                'card' => sprintf("%'013d\n", $record->card),
                                'user_name' => $record->user_name,
                                'discount_old' => $record->discount,
                                'discount_new' => $discount,
                                'vip' => $is_vip,
                                'result' => 0
                            ];

                            if(+$record->discount != +$discount) {
                                $discountOld = $record->discount;
                                $record->discount = $discount;
                                //$result = false;
                                $result = $record->update(false);
                                $log[+$record->card]['result'] =  $result;

                                if(!$is_vip && $discount) {
                                    $this->sendNotifyMail($record);

                                    $old = [
                                        'discount' => $discountOld
                                    ];
                                    $new = [
                                        'discount' => $discount,
                                        'user_name' => $record->user_name,
                                        'email' => $record->email ? $record->email : 'email не указан',
                                        'card' => $record->card
                                    ];

                                    (new History())->setRow(Yii::$app->controller, $old, $new,  'Отправка уведомления об изменении скидки');
                                }

                                $historyOld[$record->card] = $discountOld;
                                $historyNew[$record->card] = $discount;
                            }
                        }
                    }

                    if(!empty($log)) {
                        $updatelog = $this->printResult($log);
                    }
                }
            }
            else{
                Yii::$app->session->addFlash('danger', 'Ошибка загрузки файла '.$model->sync);
            }

            (new History())->setRow(Yii::$app->controller, $historyOld, $historyNew, 'Синхронизация с 1С ');
        }

        return $this->render('sync', [
            'model' => $model,
            'updatelog' => $updatelog
        ]);
    }

    /**
     * Отправка уведомления клиенту об изменении размера скидки
     *
     * @param $record
     */
    public function sendNotifyMail($record = null) {
        if($record) {

            if ($record->email) {
                $params = [
                    'user_name' => $record->user_name,
                    'card'      => $record->card,
                    'discount'  => $record->discount,
                    'discount_data'  => $this->LOYALITY[$record->discount],
                ];

                $message = Yii::$app->mailer->compose('mail-discount-notify', $params);
                $send = $message
                    ->setTo($record->email)
                    //->setTo('vkurlenko@yandex.ru')
                    ->setBcc('vkurlenko@yandex.ru')
                    ->setFrom(['loyalty@lion-of-porches.ru' => 'Lion Of Porches'])
                    ->setSubject('Изменение размера скидки до '.$record->discount.'%')
                    //->setTextBody('Текст сообщения')
                    ->send();
            }
        }
    }

    /**
     * Печать таблицы результатов импорта из 1С
     *
     * @param array $log
     * @return string
     */
    public function printResult($log = [])
    {
        if(empty($log)) {
            return '';
        }

        $html = '<table id="sync-log" class="table table-bordered">';
        $html .= '<tr><th rowspan="2">Номер карты</th><th rowspan="2">Имя клиента</th><th colspan="2">Скидка</th><th rowspan="2">VIP</th><th rowspan="2"></th></tr>';
        $html .= '<tr><th>предыдущая</th><th>новая</th></tr>';

        foreach($log as $row) {
            $class = $this->getClass($row['result']);
            $html .= '<tr class="'.$class.'">';
            foreach($row as $k => $v) {
                if($k == 'result') {
                    $html .= '<td>'.$this->RESULT[$v].'</td>';
                } elseif($k == 'id') {
                    continue;
                } elseif($k == 'card') {
                    if($row['id']) {
                        $html .= '<td>'.Html::a($v, '/data/view?id='.$row['id']).'</td>';
                    } else {
                        $html .= '<td>'.$v.'</td>';
                    }
                } elseif($k == 'vip') {
                    $vip = $v ? 'VIP' : " ";
                    $html .= '<td><strong>'.$vip.'</strong></td>';
                } else {
                    $html .= '<td>'.$v.'</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }

    public function getClass($result = null) {
        switch ($result) {
            case 0:
                return '';
                break;
            case 1:
                return 'success';
                break;
            case 2:
                return 'danger';
                break;
        }
    }

    public function getImportPath($file)
    {
        if(!$file){
            return false;
        }

        $newFileName = date('Y-m-d H-i');
        $path = 'upload/' .$newFileName . '.' . $file->extension;

        return $path;
    }

    public function getFullPath($path)
    {
        return Yii::getAlias('@webroot').'/'.$path;
    }

    /**
     * Finds the Data model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Data the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Data::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // конвертация даты из dd.mm.yyyy -> yyyy-mm-dd
    public static function convertDate($field){
        $arr = Data::find()->asArray()->all();

        foreach($arr as $str => $v){
            $date = $v[$field];
            //$date = $v['activation_date'];

            if(trim($date) != ''){
                $d = explode('.', $date);
                if(!empty($d) && count($d) == 3){
                    $new_date = $d[2].'-'.$d[1].'-'.$d[0];
                    $q = Data::findOne($v['id']);
                    $q->$field = $new_date;
                    $q->save(false);
                }
            }
        }
    }

    // конвертация gender из м/ж -> 1/0
    public static function convertGender(){
        $arr = Data::find()->asArray()->all();

        foreach($arr as $str => $v){
            $gender = $v['gender'];
            $q = Data::findOne($v['id']);

            echo $gender;

            $gender == 'м' ? $q->gender = '1' : $q->gender = '0';

            $q->save(false);
        }
    }

    // получим возраст клиента по его дате рождения
    public static function getAge($birthday){
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) {
            $age--;
        }
        return $age;
    }
}
