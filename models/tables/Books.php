<?php

namespace app\models\tables;

use Yii;
use yii\helpers\Console;

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
    public function authorsToString()
    {
        $authors_str = '';
        if (!empty($this->authors)) {
            // Выводятся только первые два автора, чтобы помещались в preview
            for ($i=0; $i<count($this->authors) && $i<2; $i++) {
                if ($i !== count($this->authors)-1) {
                    $authors_str .= '<span class="book-author">' . $this->authors[$i]->name . ',</span>';
                } else {
                    $authors_str .= '<span class="book-author">' .$this->authors[$i]->name . '</span>';
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
    public function getCroppedDescription()
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

    /**
     * @return void
     */
    public function uploadImages($books_arr)
    {
        Console::startProgress(0, count($books_arr));
        for ($i=0; $i<count($books_arr); $i++) {
            $remote_file_url = (isset($books_arr[$i]['thumbnailUrl'])) ? $books_arr[$i]['thumbnailUrl'] : '';
            $http_status = $this->getHttpStatus($remote_file_url);

            if (
                $remote_file_url !== ''
                && $http_status == 200
                && file_get_contents($remote_file_url) !== ''
                && getimagesize($remote_file_url) !== false
            ) {
                $file_name = substr(strrchr($remote_file_url, '/'), 1);

                // Проверка имени файла на уникальность
                if (file_exists(\Yii::getAlias('@app/web/img/') . $file_name) === true) {
                    $local_file_path = $this->getLocalFilePath($file_name);
                } else {
                    $local_file_path = \Yii::getAlias('@app/web/img/') . $file_name;
                }

                file_put_contents($local_file_path, file_get_contents($remote_file_url));
            }
            Console::updateProgress($i, count($books_arr));
        }
        Console::endProgress();
    }

    public function getLocalFilePath($file_name) {
        $file_name_arr = explode('.', $file_name);
        $file_base_name = $file_name_arr[0];
        $file_extension = $file_name_arr[1];

        $fi = 0;
        while (file_exists(\Yii::getAlias('@app/web/img/') . $file_base_name . '.' . $file_extension) === true) {
            $file_index_arr = explode('_', $file_base_name);

            if (count($file_index_arr) > 1 && is_numeric(end($file_index_arr))) {
                $file_base_name = substr($file_base_name, 0, strrpos($file_base_name, '_'));
            }
            $file_base_name .= '_'. ($fi++);
        }
        return \Yii::getAlias('@app/web/img/') . $file_base_name . '.' . $file_extension;
    }

    public function getHttpStatus($remote_file_url) {
        $curl = curl_init($remote_file_url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $http_status;
    }
}
