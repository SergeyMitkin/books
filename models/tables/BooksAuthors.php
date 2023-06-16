<?php

namespace app\models\tables;

use Yii;

/**
 * Составная таблица "books_authors" для книг и авторов.
 *
 * @property int $book_id
 * @property int $author_id
 *
 * @property Authors $author
 * @property Books $book
 */
class BooksAuthors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books_authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'author_id'], 'required'],
            [['book_id', 'author_id'], 'integer'],
            [['book_id', 'author_id'], 'unique', 'targetAttribute' => ['book_id', 'author_id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Authors::class, 'targetAttribute' => ['author_id' => 'id']],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'book_id' => 'Book ID',
            'author_id' => 'Author ID',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Authors::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }

    /**
     * Загрузка данных в таблицу
     * @param $books_arr
     * @return void
     */
    public function loadData($books_arr) {
        for($i=0; $i<count($books_arr); $i++) {
            if (isset($books_arr[$i]['authors'])) {
                for($ai=0; $ai<count($books_arr[$i]['authors']); $ai++) {
                    $author_id = \app\models\tables\Authors::find()
                        ->select('id')
                        ->where(['name' => $books_arr[$i]['authors'][$ai]])
                        ->one();

                    $books_authors = self::findOne([
                        'book_id' => $i+1,
                        'author_id' => (isset($author_id->id)) ? $author_id->id : null
                    ]);

                    if ($books_authors === null) {
                        $books_authors = new $this;
                    }
                    $books_authors->book_id = $i+1;
                    $books_authors->author_id = (isset($author_id->id)) ? $author_id->id : null;
                    $books_authors->save();
                }
            }
        }
    }
}
