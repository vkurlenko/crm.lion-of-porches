<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property int $id
 * @property int $user_id
 * @property string $data
 * @property string $action
 * @property string $date
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['data', 'action'], 'string'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'date' => 'Дата/время',
            'action' => 'Действие',
            'data' => 'Изменения данных',
        ];
    }

    public function setRow($controller = null, $oldModel = null, $newModel = null, $comment = null)
    {
        $action = null;

        if($controller){
            $action = $controller->id . '/' . $controller->action->id;
        }

        if($newModel){
            $data = [];

            foreach($newModel as $attr => $v){
                $old = $oldModel[$attr];
                $new = $v;
                if($old != $new){
                    $data[$attr] = [$old, $new];
                }
            }
        }

        if($comment){
            $data['comment'] = $comment;
        }

        $row = new History();
        $row->user_id = Yii::$app->user->identity->id;
        $row->action = $action;
        $row->data = serialize($data);
        $row->save(false);
    }

}
