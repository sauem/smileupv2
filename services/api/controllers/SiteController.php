<?php

namespace api\controllers;

use common\helper\HelperFunction;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\Cors;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends BaseRestController
{
    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        unset($behaviors['access']);
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];
        return $behaviors;
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionLogin()
    {
        $loginForm = new \api\models\LoginForm();
        $loginForm->load(\Yii::$app->request->post(), '');
        $loginToken = $loginForm->login();
        if (!$loginToken) {
            throw new BadRequestHttpException(HelperFunction::getFirstErrorModel($loginForm));
        }
        return [
            'user' => $loginForm->getUser(),
            'token' => $loginToken,
            'role' => $loginForm->getUser()->role
        ];
    }

}
