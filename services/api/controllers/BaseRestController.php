<?php


namespace api\controllers;


use mdm\admin\components\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class BaseRestController extends Controller
{

    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        unset($behaviors['access']);
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class
        ];
//        $behaviors['authenticator']['except'] = $this->unRequiredAuthAction();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'allowActions' => [
                '/site/login',
            ]
        ];
        return $behaviors;
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        throw new BadRequestHttpException($exception->getMessage());
    }
}
