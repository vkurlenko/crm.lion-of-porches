<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "mail".
 *
 * @property int $id
 * @property string $subject
 * @property string $body
 * @property string $mailImage
 */
class Mail extends \yii\db\ActiveRecord
{
    public $img;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject', 'body'], 'required'],
            [['subject', 'body'], 'string'],
            [['active'], 'integer'],
            [['mailImage'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Тема рассылки',
            'body' => 'Текст письма',
            'mailImage' => 'Вложение (jpg, png)',
            'active' => 'Статус рассылки'
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            //$this->img->saveAs('upload/' . $this->img->baseName . '.' . $this->img->extension);
            $this->img = 'upload/' . $this->img->baseName . '.' . $this->img->extension;
            $this->save();
            return true;
        } else {
            return false;
        }
    }

}
