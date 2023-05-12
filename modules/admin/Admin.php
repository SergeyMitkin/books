<?php

namespace app\modules\admin;

//use app\modules\admin\components\AdminAccessControl;
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
                            return \Yii::$app->user->getId() == 100;
                        },
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['admin/site'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    \Yii::$app->response->redirect(['admin']);
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
