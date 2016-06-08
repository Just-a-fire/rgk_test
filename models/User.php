<?php

namespace app\models;

use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;
use yii\db\Expression;
use app\models\Event;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;

    public static function statusDescription() {
        return [
            self::STATUS_ACTIVE => Yii::t('users', 'Active'),
            self::STATUS_INACTIVE => Yii::t('users', 'Inactive'),
        ];
    }

    public static function roleDescription() {
        return [
            self::ROLE_USER => Yii::t('users', 'User'),
            self::ROLE_ADMIN => Yii::t('users', 'Admin'),
        ];
    }

    // public $id;
    // public $username;
    // public $password;
    public $authKey;
    public $accessToken;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
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
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
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
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // return $this->password === $password;
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('users', 'Username'),
            'email' => Yii::t('users', 'Email'),
            'password' => Yii::t('users', 'Password'),
            'role' => Yii::t('users', 'Role'),
            'status' => Yii::t('users', 'Status'),
            'created_at' => Yii::t('users', 'CreatedAt'),
            'updated_at' => Yii::t('users', 'UpdatedAt'),
        ];
    }

    /**
     * @inheritdoc
     */
    // public function beforeSave($insert)
    // {
    //     if ($this->isNewRecord) {
    //         $event = new Event();
    //         $event->type = Event::TYPE_CREATE_USER;
    //         $event->description = Event::$events_description[Event::TYPE_CREATE_USER];
    //         $event->save();
    //     }

    //     return parent::beforeSave($insert);
    // }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $event = new Event();
            $event->created_by = $this->getPrimaryKey();
            $event->type = Event::TYPE_CREATE_USER;
            $event->description = Event::eventsDescription()[$event->type];
            $event->model = self::ClassName();
            $event->item_id = $this->getPrimaryKey();            
            $event->save();
        } else {
            $event = new Event();
            // $event->created_by = $this->getPrimaryKey();
            $event->created_by = Yii::$app->user->id;
            $event->type = Event::TYPE_UPDATE_USER;
            $event->description = Event::eventsDescription()[$event->type];
            $event->model = self::ClassName();
            $event->item_id = $this->getPrimaryKey(); 
            $event->changed_attributes = serialize($changedAttributes);            
            $event->save();
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Change user password.
     *
     * @param string $password Password
     *
     * @return boolean true if password was successfully changed
     */
    public function password($password)
    {
        $this->setPassword($password);
        return $this->save(false);
    }

    private static function upFirstLetter($str, $encoding = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
            . mb_substr($str, 1, null, $encoding);
    }

    public static function changedAttributesInfo($user, $changedAttributes, $type = 'html')
    {
        $eol = strtolower($type) === 'html' ? '<br>' : '\r\n';
        $us_changedAttributes = unserialize($changedAttributes);
        $cia = $eol;
        $excluded_fields = ['password', 'updated_at'];

        foreach ($us_changedAttributes as $key => $value) {
            if ($user->$key !== $value && !in_array($key, $excluded_fields)) {
                $field = Yii::t('users', self::upFirstLetter($key));
                $old_word = Yii::t('users', '{old, select, Роль{Старая} Почта{Старая} other{Старый}}', [
                    'old'   => $field,
                ]);
                $new_word = Yii::t('users', '{new, select, Роль{Новая} Почта{Новая} other{Новый}}', [
                    'new'   => $field,
                ]);
                
                $cia .= "$old_word $field - $value. $new_word $field - {$user->$key}." . $eol;
            }
        }

        return $cia;
    }
}
