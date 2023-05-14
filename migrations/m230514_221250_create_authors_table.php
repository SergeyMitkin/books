<?php

use yii\db\Migration;
use yii\helpers\Json;

/**
 * Handles the creation of table `{{%authors}}`.
 */
class m230514_221250_create_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);

        $data = file_get_contents(\Yii::getAlias('@app/data/books.json'));
        $books_arr = Json::decode($data, true);
        $authors_arr = [];

        for ($i=0; $i<count($books_arr); $i++) {
            if (isset($books_arr[$i]['authors'])) {
                for ($ai=0; $ai<count($books_arr[$i]['authors']); $ai++) {
                    $authors_arr[] = $books_arr[$i]['authors'][$ai];
                }
            }
        }

        foreach (array_unique($authors_arr) as $author) {
            $this->insert('authors', [
                'name' => $author
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%authors}}');
    }
}
