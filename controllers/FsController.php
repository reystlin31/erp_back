<?php

namespace app\controllers;

class FsController extends ApiController
{
    public $modelClass = User::class;

    public function actionAddReception()
    {
        $params = [
            'token',
            'New_or_Repeat',
            'People_or_Company',
            'manufacture',
            'model',
            'number',
            'phone',
            'name',
            'company',
            'comment',
            'equipment',
            'isQuickly',
        ];
        $request = \Yii::$app->request->post();
        self::CheckInputParams($params, $request);
        $token=$request['token'];
        $model=$request['model'];
        return $this->AddReception($token, $model);
    }

    public function AddReception($token, $model)
    {
        return $this->asjson(['error' => $model ]);
    }
}
