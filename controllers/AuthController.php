<?php

namespace app\controllers;

use Yii;
use app\models\UserToken;
use app\models\User;

class AuthController extends \yii\rest\ActiveController
{
    public $modelClass = UserToken::class;

    public function actionLogin()
    {
        $params = \Yii::$app->request->getBodyParams();
        return $params;
        $users = User::find()->where(['login' => $params['login']])->all();
        if(!$users)//Пользователь не найден
            return $this->asjson(['error' => 'Пользователь с логином '. $params['login'].' не обнаружен']);
        if($users['pass'] == $params['pass'])
        { //Пользователь авторизован
            return $this->asjson(['toekn' => '111']);
        }
        else
        { //не верный пароль
            return $this->asjson(['error' => 'Пароль введен не верно']);
        }
    }
    function actionABC() {
        self::functionCheckInputParams(['a', 'b', 'c'], \Yii::$app->request->post());
        $this->doABC ($a,$b,$c);
    }

    function doABC ($a,$b,$c) {
    echo $a,$b,$c;
    }

    static function CheckInputParams($inputCheckNeed, $currentParams)
    {
        if ( count(array_intersect_key($inputCheckNeed,$currentParams)) !== count($inputCheckNeed) )
            throw new Exception('Не все требуемые параметры переданы.');
        return true;
    }
    public function actionLogout($token)
    {
        return json_encode(['Result'=>'Ok']);
    }

    public function actionRegister()
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
    public $enableCsrfValidation = false;
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }
}
