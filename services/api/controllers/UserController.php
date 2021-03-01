<?php


namespace api\controllers;


use api\controllers\BaseActiveController;
use api\models\UserPage;

class UserController extends BaseActiveController
{
    public $modelClass = UserPage::class;
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $searchModel = new UserPage();
        return $searchModel->search(\Yii::$app->request->queryParams);
    }

}
