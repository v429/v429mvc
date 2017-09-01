<?php

namespace Core\Model;

//singlaton of mysql connection

class Connection 
{

	private static $_instance = null;
	
	private function __construct() {}

	private function __clone() {}

	public static function getInstance()
	{
		if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            $configs = include('app/config.php');

			if (!$configs) {
				die('ERROR:config file not exist!');
			}
			//set mysql connect
			self::$_instance =  new \mysqli(
				$configs['mysql']['db_host'], 
				$configs['mysql']['db_user'], 
				$configs['mysql']['db_pwd'], 
				$configs['mysql']['db_database']
			);

			//connect error
			if (self::$_instance->connect_error) {
				die('Error : ('. self::$_instance->connect_errno .') '. self::$_instance->connect_error);
			}

			//set charset
			mysqli_set_charset(self::$_instance, $configs['mysql']['db_charset']);

			return self::$_instance;
        }
        return self::$_instance;
	}


}