<?php

namespace app\controllers;

use app\models\FsTypesDevice;

class Fs_types_deviceController extends ApiController
{
    public $modelClass = 'models\FsTypesDevice';

    public function actionViews()
    {
        $types = Array();

        $data = FsTypesDevice::find()->all();

        foreach( $data as $type)
            array_push($types, $type['value']);

        return $this->asjson($types);
    }
    
    public function actionAdd()
    {
        $request = \Yii::$app->request->post();

        $dictionary = new FsTypesDevice();
        $dictionary->value = $request['value'];
        if($dictionary->save())
            return $this->asjson($dictionary->value);
        else
            return $this->asjson(0);
    }
}
?>