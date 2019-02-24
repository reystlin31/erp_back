<?php

//Возвращает список доступных порталов для пользователя
// @param Id - Id пользователя
// @return array
function getPortalsById($Id)
{
	//Проверяем сущетвует ли портал с таким именем
	$query="SELECT Portal FROM Users_Portals WHERE User='".$Id."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);
	if(mysqli_num_rows($res)) {
		$resp=Array();
		$i=0;
		while($arr=mysqli_fetch_assoc($res)) {
			$resp[$i] =  $arr['Portal'];
			$i++;
		}
		return(Array('Result' =>$resp));
	}
	else {
		return(Array('Result' => 'None'));
	}
}

//Проверяем существует ли портал с таким именем
// @param portal - название портала
// @return Result - Ok
// @return Result - Failure
function getisExists($portal)
{
	//Проверяем сущетвует ли портал с таким именем
	$query="SELECT COUNT(*) FROM `Portals` WHERE `NAME`='".$portal."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);
	if($row[0]!=0)
	{
		return(Array('Result' => 'Ok'));
	}
	return(Array('Result' => 'Failure'));
}

//Проверяем имеет ли пользователь доступ к порталу
// @param portal - название портала
// @param ID_User - ID пользователя
// @return Result - Yes
// @return Result - No
function getaccessUserForPortal($portal ,$ID_User)
{
	$query="SELECT COUNT(*) FROM `Users_Portals` WHERE `Portal`='".$portal."' AND `User`='".$ID_User."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);
	if($row[0]!=0)
	{
		return(Array('Result' => 'Yes'));
	}
	return(Array('Result' => 'No'));
}

//Добавление пользователя в портал
// @param userId - ID пользователя
// @param portal - Название портала
// @return Result - Ok
// @return error
function postAddUserToPortal($userId, $portal)
{
	//Проверяем сущетвует ли пользователь с таким ID
	$query="SELECT COUNT(*) FROM `Users` WHERE `ID`='".$userId."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);
	if($row[0]==0)
	{
		return(Array('error' => 'Пользователь с ID '.$userId.' отсутствует'));
	}

	//Проверяем сущетвует ли портал
	$query="SELECT COUNT(*) FROM `Portals` WHERE `NAME`='".$portal."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);
	if($row[0]==0)
	{
		return(Array('error' => 'Портал '.$portal.' уже существует'));
	}

	//Проверяем Привязан ли пользователь к порталу
	$query="SELECT COUNT(*) FROM `Users_Portals` WHERE `Portal`='".$portal."' AND `User`='".$userId."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);
	if($row[0]!=0)
	{
		return(Array('error' => 'Пользователь '.$userId.' уже привязан к порталу '.$portal));
	}

	//Если не суещствует, то создаем привязку
	$query = "INSERT INTO
 			`Users_Portals`(`User`,`Portal`,`status`)
 			 VALUES('".$userId."','".$portal."',2);";

	if(!mysqli_query($GLOBALS['db'], $query))
		throw new Exception( 'Ошибка добавления пользователя:'.mysqli_error($GLOBALS['db']), 405);

	return(Array('Result' => 'Ok'));
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

				case "isExists":
				if(array_key_exists('portal',$args))
					_response(getisExists($args['portal']));
				else
					throw new Exception( 'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
				break;

				case "getPortalsById":
					if(array_key_exists('Id',$args))
						_response(getPortalsById($args['Id']));
					else
						throw new Exception( 'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
					break;

				case 'accessUserForPortal':
					if(array_key_exists('Portal',$args)&&array_key_exists('ID_User',$args))
						_response(getaccessUserForPortal($args['Portal'],$args['ID_User']));
					else
						throw new Exception( 'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
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

					case "addUserToPortal":
						if(array_key_exists('userId',$args)&&array_key_exists('portal',$args))
							_response(postAddUserToPortal($args['userId'],$args['portal']));
						else
							throw new Exception( 'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
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