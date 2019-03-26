<?php

namespace app\controllers;

use Yii;
use app\models\UserToken;
use app\models\User;
use yii\db\Expression;

class AuthController extends \yii\rest\ActiveController
{
    public $modelClass = UserToken::class;

    public function actionLogin()
    {
        $params = \Yii::$app->request->post();
        
        ApiController::CheckInputParams(['login' => 0, 'pass' => 0], $params);

        $users = User::find()->where(['login' => $params['login']])->limit(1)->one();

        if(!$users)//Пользователь не найден
            return $this->asjson(['error' => 'Пользователь с логином '. $params['login'].' не обнаружен']);

        if($users['pass'] == md5(md5($params['pass'])))
        { //Пользователь авторизован
            $Token = $this->generateCode();

            $TokenRecord = new UserToken;

            $TokenRecord->ID_User = $users->ID; 
            $TokenRecord->Token = $Token;
            $TokenRecord->Create_Date = new Expression('NOW()');
            $TokenRecord->Last_Visit = new Expression('NOW()');

            $TokenRecord->save();

            return $this->asjson(['token' => $Token ]);
        }
        else
        { //не верный пароль
            return $this->asjson(['error' => 'Пароль введен не верно']);
        }
    }

    public function actionLogout($token)
    {
        UserToken::deleteAll(['Token' => $token]);
        return json_encode(['Result'=>'Ok']);
    }

    public function actionRegister()
    {

    }

    public function actionRememberPassword()
    {

    }

    public static function allowedDomains() {
        return [
             '*',                        // star allows all domains
             'http://localhost',
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application\json' => \yii\web\Response::FORMAT_JSON,
                'xml' => \yii\web\Response::FORMAT_XML
            ]
        ];
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors'  => [
                // restrict access to domains:
                'Origin'                           => static::allowedDomains(),
                'Access-Control-Request-Method'    => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => false,
                'Content-Type' => 'application/json',
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
            ],
        ];
        return $behaviors;
    }

    // Функция для генерации случайной строки
    public function generateCode($length=6)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length)
            $code .= $chars[mt_rand(0,$clen)];
        return $code;
    }

        
}
