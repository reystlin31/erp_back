<?php

//Принятие аппарат в ремонт, возвращает его Id
// @param Portal - Id Портала
// @param Id_user - Id пользователя
// @param Kind_Device - Id пользователя
// @param Kind_Counterparty - Id пользователя
// @param Device - Id пользователя
// @param Id - Id пользователя
// @param Company_Name - Id пользователя
// @param Phone_Number - Id пользователя
// @param Comment - Id пользователя
// @param Source - Id пользователя
// @return Id - Id принятого аппарата

function AddRepair($Portal, $Id_user, $Kind_Device, $Kind_Counterparty, $Device,
                   $Id, $Company_Name, $Phone_Number, $Comment, $Source )
{
    if($Kind_Device)
    {//Новый аппарат
        $res[Id]=111;
        return Array('Result'=>$res);
    }
    else
    {//Повторный ремонт
        $res[Id]=222;
        return Array('Error'=>$res);
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
                    
                case "AddRepair"://print_r($args,true);
                    if(array_key_exists('Portal',$args) && array_key_exists('Id_user',$args) && 
                        array_key_exists('Kind_Device',$args) && array_key_exists('Kind_Counterparty',$args) && 
                        array_key_exists('Device',$args) && array_key_exists('Id',$args) && 
                        array_key_exists('Company_Name',$args) && array_key_exists('Phone_Number',$args) && 
                        array_key_exists('Comment',$args) && array_key_exists('Source',$args))

                        _response(AddRepair($args['Portal'], $args['Id_user'],
                        $args['Kind_Device'], $args['Kind_Counterparty'], $args['Device'],
                        $args['Id'], $args['Company_Name'], $args['Phone_Number'],
                        $args['Comment'], $args['Source']));
					else
						throw new Exception( print_r($args,true).'Указаны не все параметры для функции '.$func.' метода '.$method, 405);
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