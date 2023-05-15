<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property Books[] $books
 * @property BooksAuthors[] $booksAuthors
 */
class Authors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::class, ['id' => 'book_id'])->viaTable('books_authors', ['author_id' => 'id']);
    }

    /**
     * Gets query for [[BooksAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooksAuthors()
    {
        return $this->hasMany(BooksAuthors::class, ['author_id' => 'id']);
    }
}
