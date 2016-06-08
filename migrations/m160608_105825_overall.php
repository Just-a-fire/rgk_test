<?php

use yii\db\Migration;

class m160608_105825_overall extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => Schema::TYPE_PK,

            'username' => 'varchar(255) NOT NULL',
            'email' => 'varchar(255) NOT NULL',
            'password' => 'varchar(255) NOT NULL',
            'role' => 'int(11) NOT NULL',
            'status' => 'int(11) NOT NULL DEFAULT 1',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NULL DEFAULT NULL',            
        ], 'ENGINE=InnoDB');

        $this->createTable('articles', [
            'id' => Schema::TYPE_PK,

            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp NULL DEFAULT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_by' => 'int(11) DEFAULT NULL',
            'title' => 'varchar(255) NOT NULL',
            'content' => 'varchar(1024) NOT NULL',
            'published' => 'tinyint(4) NOT NULL DEFAULT 1',          
        ], 'ENGINE=InnoDB');

        $this->createTable('events', [
            'id' => Schema::TYPE_PK,

            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'created_by' => 'int(11) NOT NULL',
            'type' => 'int(11) NOT NULL',
            'description' => 'varchar(255) NOT NULL',
            'model' => 'varchar(255) NOT NULL',
            'item_id' => 'int(11) NOT NULL',
            'changed_attributes' => 'varchar(255) NOT NULL',
        ], 'ENGINE=InnoDB');

        $this->createTable('notice', [
            'id' => Schema::TYPE_PK,

            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'created_by' => 'int(11) NOT NULL',
            'title' => 'varchar(255) NOT NULL',
            'event_id' => 'int(11) NOT NULL',
            'user_id' => 'int(11) NOT NULL',
            'subject' => 'varchar(255) NOT NULL',
            'content' => 'varchar(255) NOT NULL',
            'type' => 'int(255) NOT NULL',
        ], 'ENGINE=InnoDB');

        $this->insert('users',[
            'username' => 'Admin',
            'email'=> '21ivan777@mail.ru',
            'password' => '$2y$13$PsptWvKoFvnYjWjWyRGFeuOYPZKegdY3cRMtK4nnB4fBhiIx.AEDG',
            'role' => '20',
            'status' => '1',
            'created_at' => '04.06.2016 18:49:05',
        ]);

        $this->insert('users',[
            'username' => 'User',
            'email'=> '21ivan777@mail.ru',
            'password' => '$2y$13$9fF0uG2L2vvJQIbtmyTF8.YppIUfqL9dQWWPxQ9gGpe8qp6RyJGgu',
            'role' => '10',
            'status' => '1',
            'created_at' => '05.06.2016 18:49:05',
        ]);
    }

    public function down()
    {
        echo "m160608_105825_overall cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
