<?php

namespace app\controllers;

use app\models\Users;

class UsersController extends ApiController
{
    public $modelClass = Users::class;

    public function actionAuth()
    {
        return Users::find()->select('Name')->all();
    }
}
