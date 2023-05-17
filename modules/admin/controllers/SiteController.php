<?php

namespace app\modules\admin\controllers;

use app\models\AdminLoginForm;
use app\models\tables\Users;
use yii\helpers\Json;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        $data = file_get_contents('https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json');
        $books_arr = Json::decode($data, true);

        // --- ОТЛАДКА НАЧАЛО
        echo '<pre>';
        var_dump($books_arr);
        echo'</pre>';
        die;
        // --- Отладка конец
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