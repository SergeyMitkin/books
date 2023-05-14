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
        $data = file_get_contents(\Yii::getAlias('@app/data/books.json'));
        print_r(Json::decode($data, true));
    }
}