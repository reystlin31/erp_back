<?php

namespace app\controllers;

use yii\filters\auth\HttpBasicAuth;

class ApiController extends \yii\rest\ActiveController
{

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];
 
    function actionABC() {
        self::functionCheckInputParams(['a', 'b', 'c'], \Yii::$app->request->post());
        $this->doABC ($a,$b,$c);
    }

    function doABC ($a,$b,$c) {
    echo $a,$b,$c;
    }

    static function CheckInputParams($inputCheckNeed, $currentParams)
    {
      //  print_r($inputCheckNeed, false);
      //  print_r( $currentParams, false);

        if ( count(array_intersect_key($inputCheckNeed,$currentParams)) !== count($inputCheckNeed) )
            throw new \Exception('Не все требуемые параметры переданы.');
        return true;
    }
    
    public function checkAccess($action, $model=null, $params=[])
    {
        return true;
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
