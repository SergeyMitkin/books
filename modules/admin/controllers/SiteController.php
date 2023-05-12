<?php

namespace app\modules\admin\controllers;

use app\models\LoginForm;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new LoginForm();
        return $this->render('login', [
            'model' => $model
        ]);
    }
}