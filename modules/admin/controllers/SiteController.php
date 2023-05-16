<?php

namespace app\modules\admin\controllers;

use app\models\AdminLoginForm;
use app\models\tables\Books;
use app\models\tables\BooksAuthors;
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
        $data = file_get_contents(\Yii::getAlias('@app/data/books.json'));
        // --- ОТЛАДКА НАЧАЛО
//        echo '<pre>';
//        var_dump(Json::decode($data, true));
//        echo'</pre>';
//        die;
        // --- Отладка конец

//        $books = BooksAuthors::find()
//            ->select(['*'])
//            ->joinWith(['author'])
//            ->andFilterWhere(['like', 'authors.name', 'W. Frank Ableson'])
//            ->asArray()
//            ->all();

        $books = Books::find()

            ->joinWith(['authors'])
            ->andFilterWhere(['like', 'authors.name', 'W. Frank Ableson'])
            ->all()
        ;

        // --- ОТЛАДКА НАЧАЛО
        echo '<pre>';
//        var_dump($books->createCommand()->getRawSql());
        var_dump($books);
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