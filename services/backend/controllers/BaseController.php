<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class BaseController extends Controller
{
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => array_merge(['error'], $this->unRequiredAuthAction()),
                        'allow' => true,
                    ],
                    [
                        'actions' => $this->requiredAuthAction(),
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    []
                ],
            ],
        ];
    }

    public function unRequiredAuthAction()
    {
        return [];
    }

    public function requiredAuthAction()
    {
        return [];
    }

    public function denyRoles()
    {
        return [];
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            //            $this->layout = '@backend/views/layouts/empty.tpl';
            return $this->render(
                '@backend/views/site/error.php',
                [
                    'exception' => $exception
                ]
            );
        }
    }

}
