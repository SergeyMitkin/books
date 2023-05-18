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
    public $croppedDescription;

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
            'authors' => 'Authors'
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

    /**
     * Строка с иманами авторов
     */
    public function authorsToString($is_preview = true)
    {
        $authors_str = '';
        if (!empty($this->authors)) {
            // Выводятся только первые два автора, чтобы помещались в preview
            if ($is_preview === true) {
                for ($i=0; $i<count($this->authors) && $i<2; $i++) {
                    if ($i !== count($this->authors)-1) {
                        $authors_str .= '<span class="book-author">' . $this->authors[$i]->name . ',</span>';
                    } else {
                        $authors_str .= '<span class="book-author">' .$this->authors[$i]->name . '</span>';
                    }
                }
            } else {
                for ($i=0; $i<count($this->authors); $i++) {
                    if ($i !== count($this->authors)-1) {
                        $authors_str .= '<span class="book-author">' . $this->authors[$i]->name . ',</span>';
                    } else {
                        $authors_str .= '<span class="book-author">' .$this->authors[$i]->name . '</span>';
                    }
                }
            }
            return $authors_str;
        } else {
            return '';
        }
    }

    /**
     * Сокращённый вариант описания книги для вывода в превью
     */
    function getCroppedDescription()
    {
        $crop_length = 240;

        if (strlen($this->shortDescription) <= $crop_length) {
            return $this->shortDescription;
        }

        $cropped_desc = rtrim(mb_substr($this->shortDescription, 0, $crop_length), " \t.");

        // Чтобы текст не обрезался посередине слова, последнее слово удаляется
        $cropped_desc = preg_replace('/\s\w*$/', '', $cropped_desc);

        if ($cropped_desc === '') {
            return '';
        }

        if (mb_substr($cropped_desc, -1) === '?' || mb_substr($cropped_desc, -1) === '!'){
            $cropped_desc.= '..';
        } else {
            $cropped_desc.= '...';
        }

        return $cropped_desc;
    }
}
