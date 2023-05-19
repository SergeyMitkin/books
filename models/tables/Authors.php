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

    public function loadData($authors_arr)
    {
        for ($i=0; $i<count($authors_arr); $i++) {
            $author = self::findOne($i+1);
            if ($author === null) {
                $author = new $this;
            }
            $author->id = $i+1;
            $author->name = (!empty($authors_arr[$i])) ? $authors_arr[$i] : '';
            $author->save();
        }
    }
}
