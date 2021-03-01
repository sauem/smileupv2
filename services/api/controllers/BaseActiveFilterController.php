<?php


namespace api\controllers;


use common\helper\HelperFunction;
use Yii;
use yii\data\ActiveDataProvider;

class BaseActiveFilterController extends BaseActiveController
{
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];
        return $actions;
    }

    public function indexDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $filter = null;
        if ($this->action->dataFilter !== null) {
            $this->action->dataFilter = Yii::createObject([
                'class' => \yii\data\ActiveDataFilter::class,
                'searchModel' => $this->modelClass,
            ]);
            if ($this->action->dataFilter->load($requestParams)) {
                $filter = $this->action->dataFilter->build();
                if ($filter === false) {
                    return $this->action->dataFilter;
                }
            }
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        $query = $modelClass::find();
        if (!empty($filter)) {
            $query->andWhere($filter);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
