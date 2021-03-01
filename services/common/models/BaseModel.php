<?php


namespace common\models;


use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            if (empty($this->created_at))
                $this->created_at = time();
//            $this->model_type = static::getModelType();
//            $this->id = $this->getCounters();
        }
        $this->updated_at = time();

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return string
     */
    public static function getModelType()
    {
        try {
            $reflect = new \ReflectionClass(get_called_class());
            return $reflect->getShortName();
        } catch (\ReflectionException $re) {
            return "";
        }
    }
}
