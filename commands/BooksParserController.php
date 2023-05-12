<?php

namespace app\commands;

use yii\console\Controller;

class BooksParserController extends Controller
{
    /**
     * books parser
     */
    public function actionIndex() {
        $file = \Yii::getAlias('@app/data/books.json');

        $json = file_get_contents($file);
        $data = json_decode($json);
        print_r($data);
    }
}