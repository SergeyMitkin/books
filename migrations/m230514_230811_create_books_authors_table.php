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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books_authors}}');
    }
}
