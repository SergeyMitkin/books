<?php

namespace app\modules\admin\controllers;

use app\models\AdminLoginForm;
use app\models\tables\Users;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $remote_file_url = 'https://s3.amazonaws.com/AKIAJC5RLADLUMVRPFDQ.book-thumb-images/hahn.jpg';

        if (getimagesize($remote_file_url) !== false) {
            $file_name = substr(strrchr($remote_file_url, '/'), 1);

            $file_name_arr = explode('.', $file_name);
            $file_base_name = $file_name_arr[0];
            $file_extension = $file_name_arr[1];

            $fi = 0;
            while (file_exists(\Yii::getAlias('@webroot/img/') . $file_base_name . '.' . $file_extension)) {
                $file_index_arr = explode('_', $file_base_name);

                if (count($file_index_arr) > 1 && is_numeric(end($file_index_arr))) {
                    $file_base_name = substr($file_base_name, 0, strrpos($file_base_name, '_'));
                }
                $file_base_name .= '_'. ($fi++);
            }

            $local_file_path = \Yii::getAlias('@webroot/img/') . $file_base_name . '.' . $file_extension;


            file_put_contents($local_file_path, file_get_contents($remote_file_url));
        }

        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'main-login';

        if(!\Yii::$app->user->isGuest){
            $user = new Users();
            if ($user->isAdmin()){
                $this->redirect(['index']);
            }
        }

        $model = new AdminLoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            $this->redirect(['index']);
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }
}