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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books_categories}}');
    }
}
