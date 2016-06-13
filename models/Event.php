<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use app\models\User;
use app\models\Notice;

/**
*  Class Event
*/
class Event extends \yii\db\activeRecord
{
	
	const TYPE_CREATE_USER = 1;
	const TYPE_CREATE_ARTICLE = 2;
	const TYPE_UPDATE_USER = 3;
	const TYPE_UPDATE_ARTICLE = 4;

	private static $events_description = [
		self::TYPE_CREATE_USER => 'user creation',
		self::TYPE_CREATE_ARTICLE => 'article creation',
		self::TYPE_UPDATE_USER => 'user update',
		self::TYPE_UPDATE_ARTICLE => 'article update',
	];

	public static function eventsDescription() {
		return self::$events_description;
	}

	public static function tableName()
	{
		return 'events';
	}

	/**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

	public static function getAll()
	{
		$data = self::find()->all();

		return $data;
	}

	public static function getOne($id)
	{
		$data = self::find()
			->where(['id' => $id])
			->one();

		return $data;
	}

	/**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
        	switch ($this->type) {
        		case self::TYPE_CREATE_USER:
        			$sitename = Yii::$app->params['siteName'];
        			$companyname = Yii::$app->params['companyName'];
        			$user = User::findIdentity($this->created_by);

        			$userid = $this->created_by;
        			$username = $user->username;

        			// $to = '21ivan777@mail.ru';
        			$to = '21ivan777@mail.ru';
        			$from = 'yii2@mail.ru';

        			$subject = Yii::t('mail', 'Registration subject', ['sitename' => $sitename]);
        			$body = Yii::t('mail', 'Registration body html', ['sitename' => $sitename, 'username' => $username, 'userid' => $userid]);
        			// $body = "Здравствуйте, $username!<br>
        			// <br>
        			// Вы зарегистроровались на сайте $sitename.<br>
        			// Ваш id: " . $this->created_by;
	        		Yii::$app->mailer->compose()
		                ->setTo($to)
		                ->setFrom([$from => $companyname])
		                ->setSubject($subject)
		                ->setHtmlBody($body)
		                ->send();
        			break;
        		case self::TYPE_UPDATE_USER:
        			$sitename = Yii::$app->params['siteName'];
        			$companyname = Yii::$app->params['companyName'];
        			$user = User::findIdentity($this->created_by);

        			$username = $user->username;
        			$changedAttributesInfo = User::changedAttributesInfo($user, $this->changed_attributes);

        			$to = '21ivan777@mail.ru';
        			$from = 'yii2@mail.ru';

        			$subject = Yii::t('mail', 'User update subject', ['sitename' => $sitename]);
        			$body = Yii::t('mail', 'User update body html', ['sitename' => $sitename,'username' => $username, 'changedAttributesInfo' => $changedAttributesInfo]);
	        		Yii::$app->mailer->compose()
		                ->setTo($to)
		                ->setFrom([$from => $companyname])
		                ->setSubject($subject)
		                ->setHtmlBody($body)
		                ->send();
        			break;
        		case self::TYPE_CREATE_ARTICLE:
        			// TODO: mail, create 
                    $sitename = Yii::$app->params['siteName'];
                    $title = 'Создана новая статья';

                    $article = Article::findIdentity($this->item_id);
                    $subject = $article->title;
                    $content = 'На сайте '.$sitename.' добавлена новая статья {articlename}. {articlelink}';

                    $notice = new Notice();
                    $notice->created_by = Yii::$app->user->id;
                    $notice->title = $title;
                    $notice->event_id = $this->id;
                    $notice->user_id = Notice::ALL;
                    $notice->subject = $subject;
                    $notice->content = $content;
                    $notice->type = Notice::defaultType();
                    $notice->save();
                    // if ($notice->validate())
                    //     $notice->save();
                    // else
                    //     throw new Exception("Notice validate error ". serialize($notice->attributes));
                        
        			break;
        		case self::TYPE_UPDATE_ARTICLE:        			
        			break;
        		
        		default:
        			throw new Exception("Unexpected event type");
        	}            
        }

        return parent::afterSave($insert, $changedAttributes);
    }
}