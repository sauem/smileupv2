<?php


namespace api\models;

class ProfileForm extends User
{
    public function rules()
    {
        return [
            [['email'], 'email'],
            [['email'], 'unique'],
            [['email', 'full_name', 'phone'], 'string'],
            [['email', 'full_name', 'phone'], 'safe'],
        ];
    }
}
