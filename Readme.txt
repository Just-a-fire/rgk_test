Поддерживается 2 языка: русский и английски.
Для выбора языка в файле config/web.php нужно задать значение переменной $config['language'] (ru-RU|en-EN).

В файле config/db.php содержатся параметры подключенния к базе данных.
Имя базы: yii2basic
Хост: localhost
Пользователь: root
Пароль: (пусто)
Кодировка: utf8

При использовании миграции
$ php yii migrate
yes
СОздаются таблицы
users
articles
events
notice

А также создаются 2 пользователя с ролями admin и user.
Логин/пароль у них Admin/Admin, User/User.

При регистрации пользователя ему отправляется сообщение.
При изменении данных пользователя, ему отправляется сообщение с информацией об изменённых данных. (Кроме полей,
содержащихся в массиве $excluded_fields функции User::changedAttributesInfo).

Функционал уведомлений можно расширить.
Для этого не требуется изменять класс Notice. Для отправки дополнительных уведомлений он использует глобальный
объект приложения sender (Yii::$app->sender).
Его параметры заданные в  файле config/web.php

'components' => [
    'sender' => [
        'class' => 'app\custom\sender\Sender',
        'paths' => [
            'articles' => [
                'view' => '/user/articles/view/',
            ]
        ],
    ],
],

Для расширения функционала уведомлений нужно у класс Sender добавить новый флаг и описание флага.
А также создать новый класс в папке custom\sender\messager, имплементирующий интерфейс IMessager.
Все новые классы должны быть в пространстве имён namespace app\custom\sender\messager.

К примеру, нужно создать новый тип уведомлений по SMS.
В классе Sender создаём новый флаг
const FLAG_TYPE_SMS   = 4; // 1 << 2

Добавляем описание

public static function typeDescription() {
    return [
        self::FLAG_TYPE_BROWSER => 'Browser',
        self::FLAG_TYPE_EMAIL   => 'Email',
        self::FLAG_TYPE_SMS     => 'SMS',
    ];
}

И создаём сам класс SMS в папке messager

namespace app\custom\sender\messager;

use app\custom\sender\messager\IMessager;

/**
* Класс уведомлений по СМС
*/
class SMS implements IMessager
{
	public function go($user, $params) {
		// основной код
	}
}

В итоге в методе Sender::send() в цикле будут проверяться подняты ли флаги и автоматически создаваться объект 
соответствующего класса.

Флаги содержатся в типе уведомления, который задаётся в форме admin/notice/create при создании уведомления из админки.
Либо в методе Notice::defaultType() при автоматическом создании уведомления в случае события создания статьи.

