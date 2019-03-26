<?php

namespace app\controllers;

use app\models\UserToken;

class UsersTokensController extends ApiController
{
    public $modelClass = UserToken::class;

    public function actionIndex()
    {
        return $this->render('index');
    }

}
