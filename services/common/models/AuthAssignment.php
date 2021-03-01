<?php


namespace common\models;


use yii\db\ActiveRecord;

class AuthAssignment extends ActiveRecord
{
    public const ROLE_ADMIN = "Admin";
    public const ROLE_SALE = "Sale";
    public const ROLES = [
        self::ROLE_ADMIN => "Admin",
        self::ROLE_SALE => "Sale",
    ];

}
