<?php


namespace api\models;


use common\models\Domain;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;
    public $reCaptcha;
    private $_user;
    public $isShowCaptcha = false;

    public function rules()
    {
        $rules = [
            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
        return $rules;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->comparePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function validateActivated($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user && ($user->status == User::STATUS_INACTIVE || $user->status == User::STATUS_DELETED)) {
                $this->addError($attribute, 'Tài khoản đã bị vô hiệu hóa.');
            }
        }
    }

    /**
     * @return bool|string
     * @throws \yii\base\Exception
     */
    public function login()
    {
        if ($this->validate()) {
            return $this->getUser()->saveLoginJWT();
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->email);
        }
        return $this->_user;
    }

}
