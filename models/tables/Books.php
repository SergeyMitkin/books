<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $isbn
 * @property int|null $pageCount
 * @property string|null $publishedDate
 * @property string|null $thumbnailUrl
 * @property string|null $shortDescription
 * @property string|null $longDescription
 * @property string|null $status
 *
 * @property Authors[] $authors
 * @property BooksAuthors[] $booksAuthors
 * @property BooksCategories[] $booksCategories
 * @property Categories[] $categories
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pageCount'], 'integer'],
            [['shortDescription', 'longDescription'], 'string'],
            [['title', 'isbn', 'publishedDate', 'thumbnailUrl', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'isbn' => 'Isbn',
            'pageCount' => 'Page Count',
            'publishedDate' => 'Published Date',
            'thumbnailUrl' => 'Thumbnail Url',
            'shortDescription' => 'Short Description',
            'longDescription' => 'Long Description',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Authors::class, ['id' => 'author_id'])->viaTable('books_authors', ['book_id' => 'id']);
    }

    /**
     * Gets query for [[BooksAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooksAuthors()
    {
        return $this->hasMany(BooksAuthors::class, ['book_id' => 'id']);
    }

    /**
     * Gets query for [[BooksCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooksCategories()
    {
        return $this->hasMany(BooksCategories::class, ['book_id' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::class, ['id' => 'category_id'])->viaTable('books_categories', ['book_id' => 'id']);
    }
}
