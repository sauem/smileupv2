<?php


namespace api\controllers;


use api\models\ProfileForm;
use common\helper\HelperFunction;
use yii\web\BadRequestHttpException;

class ProfileController extends BaseRestController
{
    public $_user = null;

    public function actionLogout()
    {
        \Yii::$app->user->getIdentity()->logout();
        return true;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionCheckToken()
    {
        $newToken = null;
        if (\Yii::$app->user->getIdentity()->tokenExpire - time() < 3600 * 4) {
            $newToken = \Yii::$app->user->getIdentity()->saveLoginJWT(false);
        }
        return [
            'user' => \Yii::$app->user->getIdentity(),
            'role' => \Yii::$app->user->getIdentity()->role,
            'token' => $newToken,
            'client_version' => CLIENT_VERSION
        ];
    }

    public function actionIndex()
    {
        return $this->findModel();
    }

    public function actionUpdatePassword()
    {
        $userId = \Yii::$app->request->post('id');
        $newPassword = \Yii::$app->request->post('newPassword');
        $rePassword = \Yii::$app->request->post('rePassword');
        $oldPassword = \Yii::$app->request->post('oldPassword');
        $user = ProfileForm::findOne($userId);
        $comparePassword = $user->comparePassword($oldPassword);
        if (!$comparePassword) {
            throw new BadRequestHttpException('Mật khẩu không chính xác!');
        }
        if ($newPassword !== $rePassword) {
            throw new BadRequestHttpException('Mật khẩu không trùng khớp!');
        }
        $user->password = \api\models\User::generatePassword($newPassword, $user->email, $user->secret_key);
        if (!$user->save()) {
            throw new BadRequestHttpException(HelperFunction::getFirstErrorModel($user));
        }
        return $user;
    }

    /**
     * @return ProfileForm|mixed|null
     * @throws BadRequestHttpException
     */
    public function findModel()
    {
        if (!$this->_user) {
            $this->_user = ProfileForm::findOne(\Yii::$app->user->id);
            if (!$this->_user) {
                throw new BadRequestHttpException("Không tìm được dữ liệu người dùng");
            }
        }
        return $this->_user;
    }


}
