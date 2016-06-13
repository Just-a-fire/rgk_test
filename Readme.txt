�������������� 2 �����: ������� � ���������.
��� ������ ����� � ����� config/web.php ����� ������ �������� ���������� $config['language'] (ru-RU|en-EN).

� ����� config/db.php ���������� ��������� ������������ � ���� ������.
��� ����: yii2basic
����: localhost
������������: root
������: (�����)
���������: utf8

��� ������������� ��������
$ php yii migrate
yes
��������� �������
users
articles
events
notice

� ����� ��������� 2 ������������ � ������ admin � user.
�����/������ � ��� Admin/Admin, User/User.

��� ����������� ������������ ��� ������������ ���������.
��� ��������� ������ ������������, ��� ������������ ��������� � ����������� �� ��������� ������. (����� �����,
������������ � ������� $excluded_fields ������� User::changedAttributesInfo).

���������� ����������� ����� ���������.
��� ����� �� ��������� �������� ����� Notice. ��� �������� �������������� ����������� �� ���������� ����������
������ ���������� sender (Yii::$app->sender).
��� ��������� �������� �  ����� config/web.php

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

��� ���������� ����������� ����������� ����� � ����� Sender �������� ����� ���� � �������� �����.
� ����� ������� ����� ����� � ����� custom\sender\messager, ���������������� ��������� IMessager.
��� ����� ������ ������ ���� � ������������ ��� namespace app\custom\sender\messager.

� �������, ����� ������� ����� ��� ����������� �� SMS.
� ������ Sender ������ ����� ����
const FLAG_TYPE_SMS   = 4; // 1 << 2

��������� ��������

public static function typeDescription() {
    return [
        self::FLAG_TYPE_BROWSER => 'Browser',
        self::FLAG_TYPE_EMAIL   => 'Email',
        self::FLAG_TYPE_SMS     => 'SMS',
    ];
}

� ������ ��� ����� SMS � ����� messager

namespace app\custom\sender\messager;

use app\custom\sender\messager\IMessager;

/**
* ����� ����������� �� ���
*/
class SMS implements IMessager
{
	public function go($user, $params) {
		// �������� ���
	}
}

� ����� � ������ Sender::send() � ����� ����� ����������� ������� �� ����� � ������������� ����������� ������ 
���������������� ������.

����� ���������� � ���� �����������, ������� ������� � ����� admin/notice/create ��� �������� ����������� �� �������.
���� � ������ Notice::defaultType() ��� �������������� �������� ����������� � ������ ������� �������� ������.

