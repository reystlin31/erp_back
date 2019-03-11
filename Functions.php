<?php
// Функция для генерации случайной строки
function generateCode($length=6)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length)
        $code .= $chars[mt_rand(0,$clen)];
    return $code;
}

function _requestStatus($code)
{
    $status = array(
        200 => 'OK',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    );
    return ($status[$code])?$status[$code]:$status[500];
}

function _response($data, $status = 200)
{
	header('HTTP/1.1 200 OK');

	if(isset($_SERVER["HTTP_ORIGIN"]))
		header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
	else
	    header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, access-control-allow-origin ');
   // header("Access-Control-Allow-Headers: ".$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
   // header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
    //header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    //header('Access-Control-Allow-Headers: X-Requested-With, content-type');
	echo json_encode($data);
}

function _cleanInputs($data)
{
    $clean_input = Array();
    if (is_array($data))
    {
        foreach ($data as $k => $v)
        {
            if($k=="request")
                continue;
            $clean_input[$k] = _cleanInputs($v);
        }
    }
    else
    {
        $clean_input = trim(strip_tags($data));
    }
    return $clean_input;
}

//Функция записи в лог файл
// @param $textLog - Записываемые данные в лог
function logFile($textLog)
{
	$file = 'logs/'.date('Y-m').'.log';
	$text = "\r\n=======================";
	$text .= "\r\n[".date('Y-m-d H:i:s')."]\r\n" ; //Добавим актуальную дату после текста или дампа массива
	$text .= $textLog;//Выводим переданную переменную
	$fOpen = fopen($file,'aw');
	fwrite($fOpen,stripslashes($text));
	fclose($fOpen);
}
?>