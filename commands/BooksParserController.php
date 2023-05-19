<?php

namespace app\commands;

use app\models\tables\Books;
use yii\console\Controller;
use yii\helpers\Json;

class BooksParserController extends Controller
{
    /**
     * books parser
     */
    public function actionIndex() {
        $data = file_get_contents('https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json');
        $books_arr = Json::decode($data, true);
        $books_model = new Books();

        // Изображения загружаются на сервер
        $books_model->uploadImages($books_arr);

        print_r($books_arr);
    }
}