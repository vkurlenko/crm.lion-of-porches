<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data".
 *
 * @property int $id
 * @property int $code
 * @property int $name
 * @property int $card
 * @property int $discount
 * @property string $user_name
 * @property string $gender
 * @property int $age
 * @property int $born_year
 * @property string $born_date
 * @property string $email
 * @property string $phone
 * @property string $comment
 * @property string $subscribe
 * @property string $send_status
 */
class Data extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $sync;

    public static function tableName()
    {
        return 'data3';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        /*return [
            [['code', 'name', 'card', 'user_name', 'born_date', 'user_name', 'gender', 'email', 'phone'], 'required'],
            [['code', 'name', 'card', 'discount', 'born_year', 'send_status'], 'integer'],
            [['user_name', 'gender', 'comment', 'subscribe'], 'string'],
            [['born_date'], 'safe'],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 255],
        ];*/
        return [
            [['card', 'user_name', 'activation_date', 'born_date', 'user_name', 'gender'], 'required'],
            [['card', 'discount', 'send_status'], 'integer'],
            [['user_name', 'gender', 'comment', 'subscribe', 'sms', 'sprdiscount', 'summapokupok', 'vid_card'], 'string'],
            [['born_date'], 'safe'],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 255],
            [['sync'], 'file', 'extensions' => 'xls, xlsx'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'name' => 'Наименование',
            'card' => 'Номер карты',
            'activation_date' => 'Дата активации',
            'discount' => 'Скидка',
            'sprdiscount' => 'Скидки(наценки)',
            'summapokupok' => 'Сумма покупок',
            'vid_card' => 'vid_card',
            'user_name' => 'Имя клиента',
            'gender' => 'Пол',
            'age' => 'Возраст',
            'born_year' => 'Год рождения',
            'born_date' => 'Дата рождения',
            'email' => 'Email',
            'phone' => 'Телефон',
            'comment' => 'Комментарий',
            'subscribe' => 'Подписан на рассылку',
            'sms' => 'Подписан на SMS',
            'send_status' => 'Отправлено',
            'sync' => 'Синхронизация'
        ];
    }

    public function findByCard($card = null)
    {
        if($card) {
            $card = $card * 1;
            $row = Data::find()
                ->where(['card' => $card])
                ->one();

            //echo $card * 1; die;

            return $row;
        }

        return false;
    }
}
