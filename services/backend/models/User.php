<?php

namespace backend\models;

use api\models\ProfileForm;
use common\helper\HelperFunction;
use common\models\AuthAssignment;
use common\models\BaseModel;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package common\models
 *
 * @property-read string $authKey
 */
class User extends BaseModel implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_EMAIL_VERIFIED = 0;
    const STATUS_EMAIL_UN_VERIFIED = 1;

    const SCENARIO_REGISTER = "SCENARIO_REGISTER";


    const USER_ACTIVE = 1;
    const  USER_DEACTIVE = 0;
    const STATUS_ARR = [
        User::USER_ACTIVE => "Active",
        User::USER_DEACTIVE => "Deactivated"
    ];

    public function rules()
    {
        return
            [
                [['email', 'password'], 'required'],
                [['full_name'], 'string'],
                ['email', 'unique'],
                [['status'], 'integer']
            ];
    }

    public static function tableName()
    {
        return 'user';
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username]);
    }

    public static function getModelType()
    {
        return 'user';
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return \Yii::$app->cache->get("LOGIN_" . $token);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->secret_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->secret_key === $authKey;
    }

    public static function generatePassword($password, $email, $secret_key)
    {
        return md5($email . "_" . $password . "_" . $secret_key);
    }

    public function comparePassword($password)
    {
        return $this->password === User::generatePassword($password, $this->email, $this->secret_key);
    }

    public function validatePassword($password)
    {
        return $this->comparePassword($password);
    }

    /**
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->secret_key = \Yii::$app->getSecurity()->generateRandomString(32);
        $this->password = User::generatePassword($password, $this->email, $this->secret_key);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
//        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            try {
                $this->username = $this->email;
                $this->setPassword($this->password);
                $this->status = User::STATUS_ACTIVE;
            } catch (\Exception $e) {
                $this->addError('email', $e->getMessage());
                return false;
            }
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function getAll($id)
    {
        return User::findAll($id);
    }

    public function getDataFromUser($userForm)
    {
        $this->email = $userForm->email;
        $this->email = $userForm->email;
        $this->status = $userForm->status;
        $this->full_name = $userForm->fullname;
    }

    public function logout()
    {
    }

    public function getRole()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'item_name']);
    }
}
