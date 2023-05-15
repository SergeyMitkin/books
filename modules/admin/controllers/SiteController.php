<?php

namespace app\modules\admin\controllers;

use app\models\AdminLoginForm;
use app\models\tables\Books;
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
        // --- ОТЛАДКА НАЧАЛО
        echo '<pre>';
        var_dump(Books::getJsonData());
        echo'</pre>';
        die;
        // --- Отладка конец


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