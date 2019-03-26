<?php

//Возвращает список доступных модулей для портала
// @param Id - Id пользователя
// @return array
function getModulesByPortal($portal)
{
	//Проверяем сущетвует ли портал с таким именем
	$query="SELECT Modules.Module, `Name` FROM Modules 
        JOIN Modules_Portals ON Modules.Module = Modules_Portals.Module
        WHERE Portal='".$portal."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);
	if(mysqli_num_rows($res)) {
		$resp=Array();
		$i=0;
		while($arr=mysqli_fetch_assoc($res)) {
            $resp[$i]['Module'] =  $arr['Module'];
            $resp[$i]['Name'] =  $arr['Name'];
			$i++;
		}
		return(Array('Result' =>$resp));
	}
	else {
		return(Array('Result' => 'None'));
	}
}

function main($method, $func, $args)
{
	switch($method)
	{
		case 'GET':
			switch($func)
			{
				case 'test':
					_response(Array('Res' => 'GET Ok'.json_encode($args)));
					break;

                case "getModulesByPortal":
					if(array_key_exists('Portal',$args))
						_response(getModulesByPortal($args['Portal']));
					else
						throw new Exception( print_r($args,true).'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
					break;

				default:
					throw new Exception( 'Invalid Function:'.$func, 404);
			}
			break;

		case 'POST':
			switch($func)
			{
				case 'test':
					_response(Array('Res' => 'POST Ok'.json_encode($args)));
					break;

				default:
					throw new Exception( 'Invalid Function:'.$func, 404);
			}
			break;

		case 'PATCH':
			switch($func)
			{
				case 'test':
					_response(Array('Res' => 'PATCH Ok'.json_encode($args)));
					break;
				default:
					throw new Exception( 'Invalid Function:'.$func, 404);
			}
			break;

		case 'DELETE':
			switch($func)
			{
				case 'test':
					_response(Array('Res' => 'DELETE Ok'.json_encode($args)));
					break;
				default:
					throw new Exception( 'Invalid Function:'.$func, 404);
			}
			break;
	}
}
?>