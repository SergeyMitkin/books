<?php

namespace app\commands;

use app\models\tables\Books;
use yii\console\Controller;
use yii\helpers\Json;

class BooksParserController extends Controller
{
    private $books_url = 'https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json';
    /**
     * Парсинг книг из файла
     */
    public function actionIndex() {
        $data = file_get_contents($this->books_url);
        $books_arr = Json::decode($data, true);
        $books_model = new Books();

        $books_model->loadData($books_arr);
    }
}