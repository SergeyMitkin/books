<?php

use yii\db\Migration;
use yii\helpers\Json;

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
            'shortDescription' => $this->text(),
            'longDescription' => $this->text(),
            'status' => $this->string(),
        ]);

        $data = file_get_contents(\Yii::getAlias('@app/data/books.json'));
        $books_arr = Json::decode($data, true);

        for ($i=0; $i<count($books_arr); $i++) {
            $this->insert('books', [
                'title' => (isset($books_arr[$i]['title'])) ? $books_arr[$i]['title'] : null,
                'isbn' => (isset($books_arr[$i]['isbn'])) ? $books_arr[$i]['isbn'] : null,
                'pageCount' => (isset($books_arr[$i]['pageCount'])) ? $books_arr[$i]['pageCount'] : null,
                'publishedDate' => (isset($books_arr[$i]['publishedDate']['$date'])) ? $books_arr[$i]['publishedDate']['$date'] : null,
                'thumbnailUrl' => (isset($books_arr[$i]['thumbnailUrl'])) ? $books_arr[$i]['thumbnailUrl'] : null,
                'shortDescription' => (isset($books_arr[$i]['shortDescription'])) ? $books_arr[$i]['shortDescription'] : null,
                'longDescription' => (isset($books_arr[$i]['longDescription'])) ? $books_arr[$i]['longDescription'] : null,
                'status' => (isset($books_arr[$i]['status'])) ? $books_arr[$i]['status'] : null,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
