<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use Yii;
use yii\db\Expression;
use app\models\Event;

class Article extends ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 0;

    public static function statusDescription() {
        return [
            self::STATUS_PUBLISHED => Yii::t('articles', 'Published'),
            self::STATUS_UNPUBLISHED => Yii::t('articles', 'Unpublished'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%articles}}';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()')
            ],
            'AttributeBehavior' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by'],
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
            'created_at' => Yii::t('articles', 'CreatedAt'),
            'updated_at' => Yii::t('articles', 'UpdatedAt'),
            'created_by' => Yii::t('articles', 'CreatedBy'),
            'updated_by' => Yii::t('articles', 'UpdatedBy'),
            'title' => Yii::t('articles', 'Title'),
            'content' => Yii::t('articles', 'Content'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $event = new Event();
            $event->created_by = Yii::$app->user->id;
            $event->type = Event::TYPE_CREATE_ARTICLE;
            $event->description = Event::eventsDescription()[$event->type];
            $event->model = self::ClassName();
            $event->item_id = $this->getPrimaryKey(); 
            $event->save();
        } else {
            $event = new Event();
            $event->created_by = Yii::$app->user->id;
            $event->type = Event::TYPE_UPDATE_ARTICLE;
            $event->description = Event::eventsDescription()[$event->type];
            $event->model = self::ClassName();
            $event->item_id = $this->getPrimaryKey(); 
            $event->changed_attributes = serialize($changedAttributes);            
            $event->save();
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    // private static function upFirstLetter($str, $encoding = 'UTF-8')
    // {
    //     return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
    //         . mb_substr($str, 1, null, $encoding);
    // }
}
