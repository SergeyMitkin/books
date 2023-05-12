<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Json;

class BooksParserController extends Controller
{
    /**
     * books parser
     */
    public function actionIndex() {
        $file = \Yii::getAlias('@app/data/books.json');

        $json = file_get_contents($file);
        $data = Json::decode($json, true);
        print_r($data);
    }
}