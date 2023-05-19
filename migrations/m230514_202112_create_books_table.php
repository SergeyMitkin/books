<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m230514_202112_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'isbn' => $this->string(),
            'pageCount' => $this->integer(),
            'publishedDate' => $this->string(),
            'thumbnailUrl' => $this->string(),
            'localFilePath' => $this->string(),
            'shortDescription' => $this->text(),
            'longDescription' => $this->text(),
            'status' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
