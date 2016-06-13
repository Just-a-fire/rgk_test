<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use Yii;
use yii\db\Expression;
use yii\base\InvalidValueException;

class Notice extends ActiveRecord
{

    const ALL = 0;

    public static function typeDescription() {
        // return $this->sender::typeDescription();
        return Yii::$app->sender->typeDescription();
    }

    public static function defaultType()
    {
        // return $this->sender::defaultType();
        return Yii::$app->sender->defaultType();
    }

    public function rules()
    {
        return [
            ['title', 'required'],            
            ['subject', 'required', 'on' => 'create'],
            ['user_id', 'required'],
            ['event_id', 'required'],
            ['type', function ($attribute, $params) {
                if (!$this->$attribute)
                    $this->addError($attribute, $attribute . ' must be chosen!');                
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notice}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function getAll()
    {
        $data = self::find()->all();
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()')
            ],
            'AttributeBehavior' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by'],
                ],
                'value' => Yii::$app->user->id,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('notice', 'Title'),
            'created_at' => Yii::t('notice', 'CreatedAt'),
            'created_by' => Yii::t('notice', 'CreatedBy'),
            'event_id' => Yii::t('notice', 'EventId'),
            'user_id' => Yii::t('notice', 'UserId'),
            'subject' => Yii::t('notice', 'Subject'),
            'content' => Yii::t('notice', 'Content'),
            'type' => Yii::t('notice', 'Type'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $attr = $this->attributes;
            Yii::$app->sender->send($attr);
        }

        return parent::afterSave($insert, $changedAttributes);
    }
}
