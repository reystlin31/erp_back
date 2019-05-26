<?php

namespace app\controllers;

use yii\filters\auth\HttpBasicAuth;

class ApiController extends \yii\rest\ActiveController
{

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];
 
    public function a($b)
    {
        return $b;
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
        //print_r($inputCheckNeed, false);
        //print_r( $currentParams, false);
        $inputCheckNeedChanged = array();
        foreach($inputCheckNeed as $value)
        {
            $inputCheckNeedChanged[$value] = 0;
        }
        if ( count(array_intersect_key($inputCheckNeedChanged, $currentParams)) !== count($inputCheckNeedChanged) )
            throw new \Exception('Не все требуемые параметры переданы.');
        return true;
    }
    
    public function checkAccess($action, $model=null, $params=[])
    {
        return true;
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
        /*$behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];*/
        return $behaviors;
    }
}
