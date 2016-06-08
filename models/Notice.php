<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use Yii;
use yii\db\Expression;
use yii\base\InvalidValueException;
use yii\helpers\BaseUrl;
use app\models\Event;

class Notice extends ActiveRecord
{
    // выражения в константах не поддерживаются в PHP < 5.6
    const FLAG_TYPE_EMAIL   = 1; // 1 << 0
    const FLAG_TYPE_BROWSER = 2; // 1 << 1

    public static function typeDescription() {
        return [
            self::FLAG_TYPE_EMAIL   => 'email',
            self::FLAG_TYPE_BROWSER => 'browser',
        ];
    }

    const ALL = 0;

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
            $author = User::findIdentity($this->created_by);
            $username = $author ? $author->username : $this->created_by;
            $title = $this->title;
            $event = Event::findIdentity($this->event_id);
            $user_id = $this->user_id;
            $subject = $this->subject;
            $content = $this->content;

            $companyname = Yii::$app->params['companyName'];
            $baseUrl = BaseUrl::base(true);
            $article_path = '/user/articles/view/';

            $event_object = '';
            if ($event->type == '1' || $event->type == '3') {
                $event_object = 'user';
                $search = ['{username}', '{userid}'];
                $replace = [$username, $user_id];
            } else if ($event->type == '2' || $event->type == '4') {
                $event_object = 'article';
                $article = Article::findIdentity($event->item_id);
                
                $search = ['{articlename}', '{articlelink}'];
                $replace = [$article->title, $baseUrl . $article_path . $article->id];
            } else {
                throw new InvalidValueException("Unexpected event type: " . $event->type);                
            }
            $subject = str_replace($search, $replace, $subject);
            $content = str_replace($search, $replace, $content);

            if ($this->type & self::FLAG_TYPE_EMAIL) {

                if ($user_id == self::ALL) { // общая рассылка
                    $users = User::getAll();
                    foreach ($users as $user_) {
                        if ($event_object == 'user') {
                            $search = ['{username}', '{userid}'];
                            $replace = [$user_->name, $user_->id];
                        } else {
                            $search = ['{articlename}', '{articlelink}'];
                            $articlelink = '<a href="' . $baseUrl . $article_path . $article->id . '">Читать</a>';
                            $replace = [$article->title, $articlelink];
                        }
                        $subject = str_replace($search, $replace, $subject);
                        $content = str_replace($search, $replace, $content);
                        $addresseename = $user_->username;
                        // $to = '21ivan777@mail.ru';
                        $to = $user_->email;
                        $from = 'yii2@mail.ru';
                        Yii::$app->mailer->compose()
                            ->setTo($to)
                            ->setFrom([$from => $companyname])
                            ->setSubject($subject)
                            ->setHtmlBody($content)
                            ->send(); 
                    }
                } else {
                    $addressee = User::findIdentity($this->user_id);
                    if ($addressee) {
                        if ($event_object == 'user') {
                            $search = ['{username}', '{userid}'];
                            $replace = [$addressee->name, $addressee->id];
                        } else {
                            $search = ['{articlename}', '{articlelink}'];
                            $replace = [$article->title, $baseUrl . $article_path . $article->id];
                        }                        
                        $subject = str_replace($search, $replace, $subject);
                        $content = str_replace($search, $replace, $content);
                        $addresseename = $addressee->username;
                        // $to = '21ivan777@mail.ru';
                        $to = $addressee->email;
                        $from = 'yii2@mail.ru';
                        Yii::$app->mailer->compose()
                            ->setTo($to)
                            ->setFrom([$from => $companyname])
                            ->setSubject($subject)
                            ->setHtmlBody($content)
                            ->send(); 
                    }   
                }
                
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }
}
