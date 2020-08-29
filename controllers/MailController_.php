<?php

namespace app\controllers;

use app\models\Data;
use Yii;
use app\models\Mail;
use app\models\MailSearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\validators\EmailValidator;
use app\models\History;

/**
 * MailController implements the CRUD actions for Mail model.
 */
class MailController extends Controller
{
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
                        'roles' => ['admin', 'manager'],
                    ],
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
     * Lists all Mail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mail model.
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
     * Creates a new Mail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mail();
        $oldModel = $model->attributes;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->mailImage = UploadedFile::getInstance($model, 'mailImage');
            $model->upload();
            $newModel = $model->attributes;
            (new History())->setRow(Yii::$app->controller, $oldModel, $newModel, 'Создана новая рассылка '.$model['subject']);

            return $this->redirect(['update', 'id' => $model->id]);
            //return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $model->attributes;

        $img = $model->mailImage;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //$model->mailImage = UploadedFile::getInstance($model, 'mailImage');
            $file = UploadedFile::getInstance($model, 'mailImage');

            if(!empty($file)){
                $file->saveAs('upload/' . $file->baseName . '.' . $file->extension);
                $model->mailImage = 'upload/' . $file->baseName . '.' . $file->extension;
                $model->save();
            }
            else{
                $model->mailImage = $img;
                $model->save();
            }
            $newModel = $model->attributes;
            (new History())->setRow(Yii::$app->controller, $oldModel, $newModel, 'Изменена рассылка '.$model['subject']);

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $oldModel = $this->findModel($id);
        $this->findModel($id)->delete();

        (new History())->setRow(Yii::$app->controller, $oldModel, [], 'Удалена рассылка '.$oldModel['subject']);

        return $this->redirect(['index']);
    }


    /**
     * запуск новой рассылки
     */
    public function actionSubscribe(){

        // обнулим флаги отправки
        $init = self::initSubscribe();

        if($init){
            // todo не отправляется первая пачка писем, но статусы отправки меняются на 1
            //$this->actionSend();
        }

        return $this->redirect(['index']);
    }

    /**
     * запуск тестовой рассылки
     */
    public function actionTest($id){

        // сформируем письмо
        $params = self::getMailFields($id);
        $to = 'vkurlenko@yandex.ru';

        $message = Yii::$app->mailer->compose('mail-subscribe', $params);

        $send = $message
            ->setTo($to)
            ->setBcc('vkurlenko@mail.ru')
            //->setFrom('lofporches@yandex.ru')
            ->setFrom(['loyalty@lion-of-porches.ru' => 'Lion Of Porches'])
            ->setSubject($params['subject'].' (тестовая рассылка)')
            /*->setHtmlBody($params['body'])
            ->setTextBody($params['body'])*/
            ->send();

        if($send){
            Yii::$app->session->addFlash('success', 'Тестовое письмо отправлено на адрес '.$to);
        } else {
            Yii::$app->session->addFlash('danger', 'Ошибка отправки тестового письма');
        }

        return $this->redirect(['index']);
    }


    /**
     * инициализация рассылки
     */
    public static function initSubscribe()
    {
        $init = false;

        // обнулим флаги отправки у всех клиентов
        Data::updateAll(['send_status' => 0], 'send_status = 1');
        //Yii::$app->session->addFlash('success', 'Обнулили все статусы');

        // установим активную рассылку
        if (Yii::$app->request->get('id')){
            $id = Yii::$app->request->get('id');

            Mail::updateAll(['active' => 0]);
            $subscribe = Mail::findOne($id);
            $subscribe->active = 1;
            $init = $subscribe->save();

            Yii::$app->session->addFlash('success', 'Инициализация рассылки прошла успешно');
            (new History())->setRow(Yii::$app->controller, [], [], 'Запущена рассылка '.$subscribe['subject']);

        }
        else
            Yii::$app->session->addFlash('danger', 'Нет ID рассылки');

        return $init;
    }

    /**
     * Получим очередную пачку адресов
     */
    public static function getEmail(){
        $arr = [];

        $emails = Data::find()
            ->where(['send_status' => 0])
            ->andWhere(['=', 'subscribe', '1'])
            ->andWhere(['!=', 'email', ''])
            ->asArray()
            ->orderBy(['id' => SORT_ASC])
            ->limit(5)
            ->all();

        //debug($emails);

        foreach($emails as $row)
            $arr[$row['id']] = $row['email'];

        return $arr;
    }

    /**
     * сформируем поля письма
     */
    public static function getMailFields($id = null){

        if($id){
            $m = Mail::find()->where(['id' => $id])->one();
        } else {
            $m = Mail::find()->where(['active' => 1])->one();
        }


        $body = self::getHtml($m->body);
        //$body = $m->body;

        $params = [
            'subject'   => $m->subject,
            'body'      => $body,
            'image'     => dirname(__DIR__).'/web/'.$m->mailImage
        ];

        return $params;
    }

    public static function getHtml($html){
        $str = '';
        $img = [];

        preg_match_all('/<img[^>]+>/i',$html, $result);

        foreach($result as $tag => $v){
            foreach($v as $i){
                preg_match('%<img.*?src=["\'](.*?)["\'].*?/>%i', $i, $str);

                $str[1] = self::getBase64($str[1]);

                $img[] = $str;
            }
        }
        //debug($img);

        foreach($img as $inner){
            $html = str_ireplace($inner[0], $inner[1], $html);
        }
        //echo $html;
        return $html;

        //return str_ireplace()

    }

    public static function getBase64($src = null){
        $imageHTML = '';

        $file = dirname(__DIR__).'/web/'.$src;
        $imageSize = getimagesize($file);
        $imageData = base64_encode(file_get_contents($file));
        //$imageHTML = "<img src='data:{$imageSize['mime']};base64,{$imageData}' {$imageSize[3]} />";
        $imageHTML = "<img src='data:{$imageSize['mime']};base64,{$imageData}' />";

        //echo htmlspecialchars($imageHTML);

        return $imageHTML;
    }


    public static function isActiveSubscribe(){
        $m = Mail::find()->where(['active' => 1])->one();

        if(!empty($m))
            return true;
        else
            return false;
    }


    /**
     * собственно пакетная рассылка
     */
    public static function actionSend(){

        if(self::isActiveSubscribe()){

            // выберем пачку очередных адресов, где send_status == 0
            $arr = self::getEmail();

            // сформируем письмо
            $params = self::getMailFields();

            $message = Yii::$app->mailer->compose('mail-subscribe', $params);

            foreach($arr as $user_id => $user_email){
                $send = false;

                $validator = new EmailValidator();
                if ($validator->validate($user_email, $error)) {
                    $send = $message
                        ->setTo($user_email)
                        //->setBcc('vkurlenko@yandex.ru')
                        //->setFrom('lofporches@yandex.ru')
                        ->setFrom(['loyalty@lion-of-porches.ru' => 'Lion Of Porches'])
                        ->setSubject($params['subject'])
                        ->setHtmlBody($params['body'])
                        ->send();
                }
                else{
                    $send = false;
                }

                $u = Data::findOne($user_id);

                if($send){
                    $u->send_status = 1;
                }
                else{
                    $u->send_status = 2;
                }
                $u->update(false);
            }
        }
    }

    /**
     * Остановить рассылку
     */
    public function actionStop(){
        Mail::updateAll(['active' => 0]);

        (new History())->setRow(Yii::$app->controller, [], [], 'Остановлена рассылка');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Mail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public static function getSubscribeStatus(){
        $arr = [];

        $arr['all'] = Data::find()->where(['subscribe' => '1'])->count();
        $arr['status0'] = Data::find()->where(['send_status' => 0])->count();
        $arr['status1'] = Data::find()->where(['send_status' => 1])->count();
        $arr['status2'] = Data::find()->where(['send_status' => 2])->count();
        $arr['status3'] = Data::find()->where(['email' => ''])->count();
//debug($arr);
        return $arr;

    }
}
