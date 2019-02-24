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
//Шаблонизатор
function Load_Page($url)
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    if($err)
    {
        echo "Error ".$err.":".$errmsg;
        exit();
    }
    return $content;
}

function Template($tmp,$vars = array())
{
    if(file_exists('Templates/'.$tmp))
    {
        ob_start();
        extract($vars);
        require 'Templates/'.$tmp;
        return ob_get_clean();
    }
    else
    {
        echo "Шаблон ".$tmp." не найден";
        exit();
    }
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
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	else
		header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
	header('Access-Control-Allow-Headers: Origin, Content-Type');
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