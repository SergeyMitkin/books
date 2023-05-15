<?php

use yii\db\Migration;
use yii\helpers\Json;

/**
 * Handles the creation of table `{{%books_categories}}`.
 */
class m230515_004534_create_books_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books_categories}}', [
            'book_id' => $this->integer(),
            'category_id' => $this->integer()
        ]);

        $this->addPrimaryKey('pk_books_categories_book_id_category_id', 'books_categories', ['book_id', 'category_id']);
        $this->addForeignKey(
            'fk-books_categories-book_id',
            'books_categories',
            'book_id',
            'books',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-books_categories-category_id',
            'books_categories',
            'category_id',
            'categories',
            'id',
            'CASCADE'
        );

        $data = file_get_contents(\Yii::getAlias('@app/data/books.json'));
        $books_arr = Json::decode($data, true);

        for($i=0; $i<count($books_arr); $i++) {
            if (isset($books_arr[$i]['categories'])) {
                for($ai=0; $ai<count($books_arr[$i]['categories']); $ai++) {
                    $category_id = \app\models\tables\Categories::find()
                        ->select('id')
                        ->where(['name' => $books_arr[$i]['categories'][$ai]])
                        ->one();

                    $this->insert('books_categories', [
                        'book_id' => $i+1,
                        'category_id' => $category_id->id
                    ]);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books_categories}}');
    }
}
