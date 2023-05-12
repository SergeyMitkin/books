<?php

namespace app\modules\admin;

use app\models\tables\Users;
use yii\filters\AccessControl;

/**
 * admin module definition class
 */
class Admin extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $defaultRoute = 'site';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $users_model = new Users();
                            return \Yii::$app->user->getId() == $users_model->admin_id;
                        },
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['admin/site'],
                        'actions' => ['login']
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    \Yii::$app->response->redirect(['admin/site/login']);
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
