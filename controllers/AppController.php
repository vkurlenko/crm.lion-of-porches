<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 04.06.2018
 * Time: 15:48
 */

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AppController extends Controller
{
   /* public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
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
    }*/

    /**
     * Вывод роли
     *
     * @param null $role
     * @return string
     */
    public function renderRole($role = null)
   {
       if($role){
           $roles = self::getRoles();

           switch ($role->item_name){
               case 'admin': $class = 'text-danger';
               break;
               case 'manager': $class = 'text-success';
               break;
               default: $class = '';
               break;
           }
           $html = '<span class="'.$class.'">'.$roles[$role->item_name].'</span>';

           return $html;
       }

       return '';
   }

    /**
     * Список ролей
     *
     * @return array
     */
    public static function getRoles()
    {
        $arr = [];
        $roles = \app\models\AuthItem::find()->where(['type' => 1])->asArray()->all();

        foreach($roles as $role){
            $arr[$role['name']] = $role['description'];
        }
        return $arr;
    }

    public static function isAdmin()
    {
        return \Yii::$app->user->can('admin');
    }

    public static function isManager()
    {
        return \Yii::$app->user->can('manager');
    }

    public static function isUser()
    {
        return \Yii::$app->user->can('user');
    }

    public function printArray($arr = [])
    {
        if(is_array($arr)) {
            return '<pre>'.print_r($arr).'</pre>';
        }
    }
}