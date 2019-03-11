<?php
//Файл функций работы с пользователями

//Находим инфо о пользователе по Id
// @param Id - Id пользователя
// @return Email - email пользователя
// @return Name - Имя пользователя
// @return Patronymic - Отчество пользователя
// @return Surname - Фамилия пользователя
// @return Personal_Phone - личный телефон пользователя
// @return Birthday - день рождения пользователя
function getUserInfoById($Id)
{
	//Проверяем Id
	$query="SELECT COUNT(*) FROM `Users` WHERE `ID`='".$Id."';";
	$res = mysqli_query($GLOBALS['db'],$query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);

	if($row[0]==0)
		throw new Exception( 'Пользователя с таким Id не существует', 405);
	else
	{
		//Находим пользователя с этим токеном
		$query = "SELECT `Email`,`Name`,`Patronymic`,
 			`Surname`,`Personal_Phone`,`Birthday` FROM `Users` WHERE `ID`='" . $Id . "';";
		$res = mysqli_query($GLOBALS['db'], $query);

		if (!$res)
			throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

		return(mysqli_fetch_assoc($res));
	}

}

//Находим ID пользователя по токену
// @param token - токен авторизации
// @return ID_User - ID пользователя с текущим токеном
function getIDbyToken($token)
{
	//Проверяем токен
	$query="SELECT COUNT(*) FROM `Users_Tokens` WHERE `Token`='".$token."';";
	$res = mysqli_query($GLOBALS['db'],$query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);

	if($row[0]==0)
		throw new Exception( 'Токен не верный', 405);
	else
	{
		//Находим пользователя с этим токеном
		$query = "SELECT `ID_User` FROM `Users_Tokens` WHERE `Token`='" . $token . "';";
		$res = mysqli_query($GLOBALS['db'], $query);

		if (!$res)
			throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

		return(Array('ID_User' => mysqli_fetch_assoc($res)['ID_User']));
	}

}

//Выход из автроизации по токену
// @param token - токен авторизации
// @param Status - статус
function getLogout($token)
{
	//Проверяем токен
	$query="DELETE FROM `Users_Tokens` WHERE `Token`='".$token."';";
	$res = mysqli_query($GLOBALS['db'],$query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	return(Array('Status' => 'Ok'));
}

//Добавление пользователя
// @param login - Логин нового пользователя
// @param pass - Пароль нового пользователя
// @param email - E-mail нового пользователя
// @param name - Имя нового пользователя
// @param patronymic - Отчество нового пользователя
// @param surname - Фамилия нового пользователя
// @param personal_phone - Номер телефона нового пользователя
// @param birthday - Дата рождения нового пользователя
// @return ID - id нового пользователя
function postAdd($login, $pass, $email, $name, $patronymic,
				 $surname, $personal_phone, $birthday)
{
	//Проверяем сущетвует ли пользователь с таким именем
	$query="SELECT COUNT(*) FROM `Users` WHERE `login`='".$login."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);
	if($row[0]!=0)
	{
		return(Array('error' => 'Пользователь с именем '.$login.' уже существует'));
	}

	//Проверяем сущетвует ли пользователь с таким E-Mail
	$query="SELECT COUNT(*) FROM `Users` WHERE `EMail`='".$email."';";
	$res = mysqli_query($GLOBALS['db'], $query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);
	if($row[0]!=0)
	{
		return(Array('error' => 'Пользователь с E-Mail '.$email.' уже существует'));
	}

	//Если не суещствует, то создаем пользователя
	$query = "INSERT INTO
 			`Users`(`ID`,`login`,`pass`,`EMail`,`Name`,`Patronymic`,
 			`Surname`,`Personal_Phone`,`Birthday`,`status`)
 			 VALUES(0,'".$login."','".md5(md5($pass))."','".$email."','".$name."','".$patronymic."',
 			 '".$surname."','".$personal_phone."','".$birthday."',1);";

	if(!mysqli_query($GLOBALS['db'], $query))
		throw new Exception( 'Ошибка добавления пользователя:'.mysqli_error($GLOBALS['db']), 405);

	return(Array('ID' => mysqli_insert_id($GLOBALS['db'])));
}

//Генерация токена авторизации по логину и паролю
// @param login - Логин нового пользователя
// @param pass - Пароль нового пользователя
// @return Token - Токен авторизации
function postAuth($login, $pass)
{

	//Промеряем существует ли пользователь с таким логином
	$query="SELECT COUNT(*) FROM `Users` WHERE `login`='".$login."';";
	$res = mysqli_query($GLOBALS['db'],$query);

	if ( !$res )
		throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

	$row = mysqli_fetch_row($res);

	if($row[0]==0)
		return(Array('error' => "Пользователя с логином ".$login." не существует"));
	else
	{
		//Проверяем пароль
		$query = "SELECT `pass`,`ID` FROM `Users` WHERE `login`='" . $login . "';";
		$res = mysqli_query($GLOBALS['db'], $query);

		if (!$res)
			throw new Exception( 'Произошла ошибка:'.mysqli_error($GLOBALS['db']), 405);

		$result = mysqli_fetch_assoc($res);
		if (md5(md5($pass)) == $result['pass'])
		{
			//Пароль введен верно
			//генерируем тоекн
			$Token=generateCode();
			//записываем тоекн в базу
			$query="INSERT INTO `Users_Tokens`(`Token`,`ID_User`,`Create_Date`,`Last_Visit`)
                    				VALUES('".$Token."',".$result['ID'].",NOW(),NOW())";

			if(!mysqli_query($GLOBALS['db'],$query))
				throw new Exception( 'Произошла ошибка создания токена:'.mysqli_error($GLOBALS['db']), 405);

			return(Array('Token' => $Token));
		}
		else
			return(Array('error' => "Пароль введен не верно"));
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

				case "UserInfoById":
					if(array_key_exists('Id',$args))
						_response(getUserInfoById($args['Id']));
					else
						throw new Exception( 'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
					break;

				case "IDbyToken":
					if(array_key_exists('token',$args))
						_response(getIDbyToken($args['token']));
					else
						throw new Exception( 'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
					break;

				case "Logout":
					if(array_key_exists('token',$args))
						_response(getLogout($args['token']));
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

                case "add":
                    if(array_key_exists('login',$args)&&array_key_exists('pass',$args)&&
						array_key_exists('email',$args)&&array_key_exists('name',$args)&&
						array_key_exists('patronymic',$args)&&array_key_exists('surname',$args)&&
						array_key_exists('personal_phone',$args)&&array_key_exists('birthday',$args))
						_response(postAdd($args['login'], $args['pass'], $args['email'],
							$args['name'], $args['patronymic'], $args['surname'],
							$args['personal_phone'],$args['birthday']));

                    else
						throw new Exception( 'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
					break;

				case 'auth':
					if(array_key_exists('login',$args)&&array_key_exists('pass',$args))
						_response(postAuth($args['login'], $args['pass']));
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