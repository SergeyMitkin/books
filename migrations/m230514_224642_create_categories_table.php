<?php

use yii\db\Migration;
use yii\helpers\Json;

/**
 * Handles the creation of table `{{%categories}}`.
 */
class m230514_224642_create_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);

        $data = file_get_contents(\Yii::getAlias('@app/data/books.json'));
        $books_arr = Json::decode($data, true);
        $categories_arr = [];

        for ($i=0; $i<count($books_arr); $i++) {
            if (isset($books_arr[$i]['categories'])) {
                for ($ci=0; $ci<count($books_arr[$i]['categories']); $ci++) {
                    $categories_arr[] = $books_arr[$i]['categories'][$ci];
                }
            }
        }

        foreach (array_unique($categories_arr) as $category) {
            $this->insert('categories', [
                'name' => $category
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%categories}}');
    }
}
