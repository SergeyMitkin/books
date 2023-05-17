<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Json;

class BooksParserController extends Controller
{
    /**
     * books parser
     */
    public function actionIndex() {
        $data = file_get_contents('https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json');
        $books_arr = Json::decode($data, true);

        // Загрузка изображений на сервер
        Console::startProgress(0, count($books_arr));
        for ($i=0; $i<count($books_arr); $i++) {
            $remote_file_url = (isset($books_arr[$i]['thumbnailUrl'])) ? $books_arr[$i]['thumbnailUrl'] : '';

            $curl = curl_init($remote_file_url);
            curl_setopt($curl, CURLOPT_NOBODY, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);

            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if (
                $remote_file_url !== ''
                && $http_status == 200
                && file_get_contents($remote_file_url) !== ''
                && getimagesize($remote_file_url) !== false
            ) {
                $file_name = substr(strrchr($remote_file_url, '/'), 1);
                $file_name_arr = explode('.', $file_name);
                $file_base_name = $file_name_arr[0];
                $file_extension = $file_name_arr[1];

                // Проверка имени файла на уникальность
                $fi = 0;
                while (file_exists(\Yii::getAlias('@app/web/img/') . $file_base_name . '.' . $file_extension) === true) {
                    $file_index_arr = explode('_', $file_base_name);

                    if (count($file_index_arr) > 1 && is_numeric(end($file_index_arr))) {
                        $file_base_name = substr($file_base_name, 0, strrpos($file_base_name, '_'));
                    }
                    $file_base_name .= '_'. ($fi++);
                }

                $local_file_path = \Yii::getAlias('@app/web/img/') . $file_base_name . '.' . $file_extension;
                file_put_contents($local_file_path, file_get_contents($remote_file_url));
            }

            Console::updateProgress($i, count($books_arr));
        }
        Console::endProgress();

        print_r($books_arr);
    }
}