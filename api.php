<?php
//Формат вызова API
// endpoint/func/<arg0>/<arg1>...?<args>
// endpoint - Класс к которому обращаемся
// func - Вызываемя функция
// arg0, arg1... - Параметры
//args - GET параметры

include_once("../Config.php");
// определеяем уровень протоколирования ошибок
error_reporting(E_ALL | E_STRICT);
// определяем режим вывода ошибок
ini_set('display_errors', 'On');

require_once "Functions.php";

//Коннект к MySQL
$db = mysqli_connect($hostname,$username,$password,$dbName);
if (!$db)
{
    _response(Array('error' => 'В настоящий момент сервер базы данных не доступен'), 404);
    logFile('В настоящий момент сервер базы данных не доступен'.mysqli_error());
    exit();
}
/*
// в какой кодировке получать данные от клиента
@mysqli_query('set character_set_client="utf8"');

// в какой кодировке получать данные от БД для вывода клиенту
@mysqli_query('set character_set_results="utf8"');

// кодировка в которой будут посылаться служебные команды для сервера
@mysqli_query('set collation_connection="utf8_general_ci"');
*/

$endpoint = 0;        //класс к которому обращаемся
$method = 0;          //метод обращения
$func = 0;          //Вызываемя функция
$request = array();         //Параметры запроса
$args = array();
try
{
    $args = explode('/', rtrim($_REQUEST['request'], '/'));//Разбиваем запрос

    if(array_key_exists(0, $args))
        $endpoint = array_shift($args);//Вытаскиваем класс
    else
    	throw new Exception('No Endpoint', 404);


    if(array_key_exists(0, $args))
        $func = array_shift($args);//Вытаскиваем функцию
    else
		throw new Exception('No Function', 404);


    $method = $_SERVER['REQUEST_METHOD'];//Вытаскиваем метод

    switch($method)
    {
        case 'POST':
        case 'PATCH':
        case 'DELETE':
            $data = file_get_contents('php://input');

            //Определяем формат данных
            switch($_SERVER['CONTENT_TYPE'])
            {
                case 'application/x-www-form-urlencoded':
                    parse_str($data, $request);
                    break;
                case 'application/json':
                    $request = (array) json_decode($data);
                    break;
                default:
					throw new Exception('Invalid CONTENT_TYPE', 405);
            }

            $request= _cleanInputs($request);
            break;

        case 'GET':
            $request = _cleanInputs($_GET);
            break;

		case 'OPTIONS':
			header('HTTP/1.1 200 OK');

			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
			header('Access-Control-Allow-Headers: origin, x-requested-with, content-type,access-control-allow-origin');
			exit(0);
			break;

        default:
			throw new Exception('Invalid Method:'.$method, 405);
    }
/*
	echo "endpoint:".$endpoint;        //класс к которому обращаемся через API
	echo "<br>method:".$method;          //метод обращения
	echo "<br>func:".$func;          //Вызываемя функция
	echo "<br>reqest:";print_r(array_merge($args,$request));         //параметры функции
*/
    //Проверяем существует ли вызываемый эндпоинт и вызываем из него основную функцию
   	switch ($endpoint)
	{
		case 'Users':
			include_once ("endpoints/Users.php");
			main($method, $func, array_merge($args, $request));
			break;
		case 'Portals':
			include_once ("endpoints/Portals.php");
			main($method, $func, array_merge($args, $request));
			break;
		default:
			throw new Exception('Invalid endpoint:'.$endpoint, 405);
	}
		


}
catch (Exception $e)
{

    //Логирование ошибок
	logFile("Response code: ".$e->getCode()."\tResponse: ".$e->getMessage().
		"\r\nEndpoint: ".$endpoint."\tMethod: ".$method.
		"\tFunction: ".$func."\tArgs:\r\n".print_r(array_merge($args,$request),true));
	_response(array('error'=>$e->getMessage()),$e->getCode());
}
?>