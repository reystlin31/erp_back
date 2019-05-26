<?php

namespace app\controllers;

use app\models\FsManufacturers;

class Fs_manufacturersController extends ApiController
{
    public $modelClass = 'models\FsManufacturers';

    public function actionViews()
    {
        $types = Array();

        $data = FsManufacturers::find()->all();

        foreach( $data as $type)
            array_push($types, $type['value']);

        return $this->asjson($types);
    }
    
    public function actionAdd()
    {
        $request = \Yii::$app->request->post();

        $dictionary = new FsManufacturers();
        $dictionary->value = $request['value'];
        if($dictionary->save())
            return $this->asjson($dictionary->value);
        else
            return $this->asjson(0);
    }
}
?>