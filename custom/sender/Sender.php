<?php

namespace app\custom\sender;

use Yii;
use yii\helpers\BaseUrl;
use app\models\User;
use app\models\Event;
use app\models\Notice;
use app\models\Article;

use app\custom\sender\messager\IMessager;
/**
* 
*/
class Sender
{
	// выражения в константах не поддерживаются в PHP < 5.6
    const FLAG_TYPE_BROWSER = 1; // 1 << 0
    const FLAG_TYPE_EMAIL   = 2; // 1 << 1

	public static function typeDescription() {
        return [
            self::FLAG_TYPE_BROWSER => 'Browser',
            self::FLAG_TYPE_EMAIL   => 'Email',
        ];
    }

	public static function defaultType()
	{
		$type_description = static::typeDescription();
		$default_type = 0x0000;
		foreach ($td as $flag => $description) {
			$default_type |= intval($flag);
		}
		return $default_type;
	}

    public static function send($noticeAtributes)
    {
        $event = Event::findIdentity($noticeAtributes['event_id']);
        if (!$event) throw new InvalidValueException("Unexpected event_id: " . $noticeAtributes['event_id']);

        $title = $noticeAtributes['title'];
        $user_id = $noticeAtributes['user_id'];
        $subject = $noticeAtributes['subject'];
        $content = $noticeAtributes['content'];

        $companyname = Yii::$app->params['companyName'];
        $baseUrl = BaseUrl::base(true);
        $article_path = Yii::$app->sender->paths['articles']['view'];   // $article_path = '/user/articles/view/';

        $event_object = '';
        if ($event->type == '1' || $event->type == '3') {
            $event_object = 'user';
        } else if ($event->type == '2' || $event->type == '4') {
            $event_object = 'article';
            $article = Article::findIdentity($event->item_id);
            
            $search = ['{articlename}', '{articlelink}'];
            $articlelink = '<a href="' . $baseUrl . $article_path . $article->id . '">Читать</a>';
            $replace = [$article->title, $articlelink];

            $subject = str_replace($search, $replace, $subject);
            $content = str_replace($search, $replace, $content);
        } else {
            throw new InvalidValueException("Unexpected event type: " . $event->type);
        }

        $users = $user_id == Notice::ALL ? User::getAll() : [User::findIdentity($user_id)];

        $messager_folder = '\\messager\\';

        foreach ($users as $user) {
            if ($event_object == 'user') {
                $search = ['{username}', '{userid}'];
                $replace = [$user->username, $user->id];
                $subject = str_replace($search, $replace, $subject);
                $content = str_replace($search, $replace, $content);
            }

            $type_description = Notice::typeDescription();
            foreach ($type_description as $key => $value) {
                if ($key > self::FLAG_TYPE_BROWSER && $noticeAtributes['type'] & $key) {                    
                    $reflectionClass = new \ReflectionClass(__NAMESPACE__ . $messager_folder . $value);
                    $messager = $reflectionClass->newInstanceArgs();

                    $params = [
                        'companyname' => $companyname,
                        'subject' => $subject,
                        'content' => $content,
                    ];
                    static::makeMessage($messager, $user, $params);
                }                    
            }
        }
    }

    private static function makeMessage(IMessager $messager, $user, $params)
    {
        $messager->go($user, $params);
    }
}
?>