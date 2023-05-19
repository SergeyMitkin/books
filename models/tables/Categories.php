<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property Books[] $books
 * @property BooksCategories[] $booksCategories
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
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
        return $this->hasMany(Books::class, ['id' => 'book_id'])->viaTable('books_categories', ['category_id' => 'id']);
    }

    /**
     * Gets query for [[BooksCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooksCategories()
    {
        return $this->hasMany(BooksCategories::class, ['category_id' => 'id']);
    }

    public function loadData($categories_arr) {
        for ($i=0; $i<count($categories_arr); $i++) {
            $category = self::findOne($i+1);
            if ($category === null) {
                $category = new $this;
            }
            $category->id = $i+1;
            $category->name = (!empty($categories_arr[$i])) ? $categories_arr[$i] : '';
            $category->save();
        }
    }
}
