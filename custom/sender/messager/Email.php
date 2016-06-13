<?php
namespace app\custom\sender\messager;

use Yii;
use app\custom\sender\messager\IMessager;

/**
* 
*/
class Email implements IMessager
{
	public function go($user, $params) {
		$to = $user->email;
        $companyname = $params['companyname'];
		$subject = $params['subject'];
		$content = $params['content'];
        $from = 'yii2@mail.ru';

        Yii::$app->mailer->compose()
            ->setTo($to)
            ->setFrom([$from => $companyname])
            ->setSubject($subject)
            ->setHtmlBody($content)
            ->send();
	}
}

?>