<?php

namespace app\controllers;

use app\models\FsTypesDevice;

class FsmanufacturemodelController extends ApiController
{
    public $modelClass = 'models\FsManufactureModel';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFindOrAdd($manufacturer, $model, $type=0)
    {

    }

    public function actionTypes()
    {
        $types = FsTypesDevice::find()->all();

        return $this->asjson($types);
    }
}
