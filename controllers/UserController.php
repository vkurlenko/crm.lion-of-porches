<?php

namespace app\controllers;

use app\models\History;
use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\AuthAssignment;
use app\models\AuthItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public $role;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        //'actions' => ['adminka'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();


        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {

            $post = Yii::$app->request->post();

            if(User::findUserByEmail($post['User']['email'])){
                Yii::$app->session->setFlash('danger', 'Пользователь '.$post['User']['email'].' уже зарегистрирован!');
                return $this->redirect(['index']);
            }

            // сохраним пароль
            $model->setPassword($post['User']['password_hash']);
            $model->generateAuthKey();

            $model->save();

            // сохраним роль
            if($post['User']['role']){
                $role = new AuthAssignment();
                $role->user_id = $model->id;
                $role->item_name = $post['User']['role'];
                $role->save(false);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $model->attributes;

        if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {

            $post = Yii::$app->request->post();

            if($post['User']['role']){
                $role = AuthAssignment::find()->where(['user_id' => $model->id])->one();
                $oldModel['role'] = $role->item_name;
                $role->item_name = $post['User']['role'];
                $role->save(false);
            }

            if($post['User']['password_hash']){
                $model->setPassword($post['User']['password_hash']);
                $model->generateAuthKey();
            }

            $newModel = $model->attributes;
            /*$newModel['role'] = $post['User']['role'];
            $newModel['password_hash'] = $post['User']['role'];*/

            (new History())->setRow(Yii::$app->controller, $oldModel, $newModel);

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = (new User())->findUserById($id);

        $this->findModel($id)->delete();

        (new History())->setRow(Yii::$app->controller, null, null, 'Удален пользователь '.$user->email.' ('.$user->username.')');

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }




}
