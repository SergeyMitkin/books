<?php

namespace app\models\tables;

use Yii;

/**
 * Составная таблица "books_categories" для книг и категорий.
 *
 * @property int $book_id
 * @property int $category_id
 *
 * @property Books $book
 * @property Categories $category
 */
class BooksCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'category_id'], 'required'],
            [['book_id', 'category_id'], 'integer'],
            [['book_id', 'category_id'], 'unique', 'targetAttribute' => ['book_id', 'category_id']],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'book_id' => 'Book ID',
            'category_id' => 'Category ID',
        ];
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
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Загрузка данных в таблицу
     * @param $books_arr
     * @return void
     */
    public function loadData($books_arr) {
        for($i=0; $i<count($books_arr); $i++) {
            if (isset($books_arr[$i]['categories'])) {
                for($ci=0; $ci<count($books_arr[$i]['categories']); $ci++) {
                    $category_id = \app\models\tables\Categories::find()
                        ->select('id')
                        ->where(['name' => $books_arr[$i]['categories'][$ci]])
                        ->one();

                    $books_categories = self::findOne([
                        'book_id' => $i+1,
                        'category_id' => (isset($category_id->id)) ? $category_id->id : null
                    ]);

                    if ($books_categories === null) {
                        $books_categories = new $this;
                    }
                    $books_categories->book_id = $i+1;
                    $books_categories->category_id = (isset($category_id->id)) ? $category_id->id : null;
                    $books_categories ->save();
                }
            }
        }
    }
}
