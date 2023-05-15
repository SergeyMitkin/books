<?php

use yii\db\Migration;
use yii\helpers\Json;

/**
 * Handles the creation of table `{{%books_authors}}`.
 */
class m230514_230811_create_books_authors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books_authors}}', [
            'book_id' => $this->integer(),
            'author_id' => $this->integer()
        ]);

        $this->addPrimaryKey('pk_books_authors_book_id_author_id', 'books_authors', ['book_id', 'author_id']);
        $this->addForeignKey(
            'fk-books_authors-book_id',
            'books_authors',
            'book_id',
            'books',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-books_authors-author_id',
            'books_authors',
            'author_id',
            'authors',
            'id',
            'CASCADE'
        );

        $data = file_get_contents(\Yii::getAlias('@app/data/books.json'));
        $books_arr = Json::decode($data, true);

        for($i=0; $i<count($books_arr); $i++) {
            if (isset($books_arr[$i]['authors'])) {
                for($ai=0; $ai<count($books_arr[$i]['authors']); $ai++) {
                    $author_id = \app\models\tables\Authors::find()
                        ->select('id')
                        ->where(['name' => $books_arr[$i]['authors'][$ai]])
                        ->one();

                    $this->insert('books_authors', [
                        'book_id' => $i+1,
                        'author_id' => $author_id->id
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
        $this->dropTable('{{%books_authors}}');
    }
}
