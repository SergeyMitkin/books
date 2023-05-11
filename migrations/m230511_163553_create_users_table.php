<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m230511_163553_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(20)->notNull(),
            'password' => $this->string(255),
            'authKey' => $this->string(32),
            'accessToken' => $this->string(255)
        ]);

        $this->insert('users', [
            'id' => 100,
            'username' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'authKey' => 'test100key',
            'accessToken' => '100-token'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
