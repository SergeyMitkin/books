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
 * @property string|null $localFilePath
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
            [['title', 'isbn', 'publishedDate', 'thumbnailUrl', 'localFilePath', 'status'], 'string', 'max' => 255],
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
            'localFilePath' => 'Local File Path',
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

    public function loadData($books_arr) {
        $authors_arr = [];
        $categories_arr = [];

        Console::startProgress(0, count($books_arr));

        for ($i=0; $i<count($books_arr); $i++) {

            // При повторном парсинге обновляются старые записи и добавляются новые
            $book = self::findOne($i+1);
            if ($book === null) {
                $book = new $this;
                $book->localFilePath = (isset($books_arr[$i]['thumbnailUrl'])) ? $this->uploadImage($books_arr[$i]['thumbnailUrl']) : null;
            } else if (isset($book->thumbnailUrl) && $book->thumbnailUrl !== $books_arr[$i]['thumbnailUrl']) {
                // Если thumbnailUrl не осталось прежним, тогда загружаем изображение
                $book->localFilePath = (isset($books_arr[$i]['thumbnailUrl'])) ? $this->uploadImage($books_arr[$i]['thumbnailUrl']) : null;
            }
            $book->id = $i+1;
            $book->title = (isset($books_arr[$i]['title'])) ? $books_arr[$i]['title'] : null;
            $book->isbn = (isset($books_arr[$i]['isbn'])) ? $books_arr[$i]['isbn'] : null;
            $book->pageCount = (isset($books_arr[$i]['pageCount'])) ? $books_arr[$i]['pageCount'] : null;
            $book->publishedDate = (isset($books_arr[$i]['publishedDate']['$date'])) ? $books_arr[$i]['publishedDate']['$date'] : null;
            $book->thumbnailUrl = (isset($books_arr[$i]['thumbnailUrl'])) ? $books_arr[$i]['thumbnailUrl'] : null;
            $book->shortDescription = (isset($books_arr[$i]['shortDescription'])) ? $books_arr[$i]['shortDescription'] : null;
            $book->longDescription = (isset($books_arr[$i]['longDescription'])) ? $books_arr[$i]['longDescription'] : null;
            $book->status = (isset($books_arr[$i]['status'])) ? $books_arr[$i]['status'] : null;
            $book->save();

            // Заполняется таблица авторов
            if (isset($books_arr[$i]['authors'])) {
                for ($ai=0; $ai<count($books_arr[$i]['authors']); $ai++) {
                    $authors_arr[] = $books_arr[$i]['authors'][$ai];
                }
            }

            // Заполняется таблица категорий
            if (isset($books_arr[$i]['categories'])) {
                for ($ai=0; $ai<count($books_arr[$i]['categories']); $ai++) {
                    $categories_arr[] = $books_arr[$i]['categories'][$ai];
                }
            }
            Console::updateProgress($i, count($books_arr));
        }
        Console::endProgress();

        $authors_model = new Authors();
        $authors_model->loadData(array_unique($authors_arr));

        $categories_model = new Categories();
        $categories_model->loadData(array_unique($categories_arr));

        $books_authors_model = new BooksAuthors();
        $books_authors_model->loadData($books_arr);


        for($cati=0; $cati<count($books_arr); $cati++) {
            if (isset($books_arr[$cati]['categories'])) {
                for($cati2=0; $cati2<count($books_arr[$cati]['categories']); $cati2++) {
                    $category_id = \app\models\tables\Categories::find()
                        ->select('id')
                        ->where(['name' => $books_arr[$cati]['categories'][$cati2]])
                        ->one();

                    $books_categories = BooksCategories::findOne([
                        'book_id' => $cati+1,
                        'category_id' => (isset($category_id->id)) ? $category_id->id : null
                    ]);

                    if ($books_categories === null) {
                        $books_categories = new BooksCategories();
                    }
                    $books_categories->book_id = $cati+1;
                    $books_categories->category_id = (isset($category_id->id)) ? $category_id->id : null;
                    $books_categories ->save();
                }
            }
        }

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
     * @return string
     */
    public function uploadImage($remote_file_url)
    {
        $local_file_path = '';
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
        return $local_file_path;
    }

    public function getLocalFilePath($file_name) {
        $file_name_arr = explode('.', $file_name);
        $file_base_name = $file_name_arr[0];
        $file_extension = $file_name_arr[1];

        $i = 0;
        while (file_exists(\Yii::getAlias('@app/web/img/') . $file_base_name . '.' . $file_extension) === true) {
            $file_index_arr = explode('_', $file_base_name);

            if (count($file_index_arr) > 1 && is_numeric(end($file_index_arr))) {
                $file_base_name = substr($file_base_name, 0, strrpos($file_base_name, '_'));
            }
            $file_base_name .= '_'. ($i++);
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
