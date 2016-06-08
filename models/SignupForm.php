<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
* Sign up form
*/
class SignupForm extends Model
{
	public $username;
    public $email;
    public $password;
	public $verifyCode;

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['username', 'email', 'password'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('form', 'Username'),
            'password' => Yii::t('form', 'Password'),
            'verifyCode' => Yii::t('form', 'verifyCode'),
        ];
    }

    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->role = User::ROLE_USER;
            $user->generateAuthKey();
            $user->save();
            Yii::$app->session->setFlash('user_id', $user->id);
            // Yii::$app->session->setFlash('signupFormSubmitted_PrimaryKey', $user->getPrimaryKey());
            
            return true;
        }
        return false;
    }
}
?>