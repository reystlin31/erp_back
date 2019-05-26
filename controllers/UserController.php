<?php

namespace app\controllers;

use app\models\User;
use app\models\UserToken;

class UserController extends ApiController
{
    public $modelClass = User::class;

    public function actionGetByToken($token)
    {
        $UserToken = UserToken::findOne($token);
        return $this->asjson($UserToken->user);
    }
}
