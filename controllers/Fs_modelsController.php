<?php

namespace app\controllers;

use app\models\FsModels;

class Fs_modelsController extends ApiController
{
    public $modelClass = 'models\FsModels';

    public function actionViews()
    {
        $types = Array();

        $data = FsModels::find()->all();

        foreach( $data as $type)
            array_push($types, $type['value']);

        return $this->asjson($types);
    }
    
    public function actionAdd()
    {
        $request = \Yii::$app->request->post();

        $dictionary = new FsModels();
        $dictionary->value = $request['value'];
        if($dictionary->save())
            return $this->asjson($dictionary->value);
        else
            return $this->asjson(0);
    }
}
?>