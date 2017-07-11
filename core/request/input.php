<?php

namespace Core\Request;

use Core\Request\Request;
use Core\Request\Validate;

class Input extends Request 
{

	protected $paramValidateErrorMsg = array();
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * get the request data
	 * 
	 * @author v429
	 * @param  [type] $name   data name
	 * @param  string $method request method
	 * @return [type]         [description]
	 */
	public static function get($name, $rule = '', $method = '')
	{
		if (!$method) {
			$method = $_SERVER['REQUEST_METHOD'];
		}
		$method = strtoupper($method);

		switch ($method) {
			case 'GET':
				$value = isset($_GET[$name]) ? $_GET[$name] : '';break;
			case 'POST':
				$value = isset($_POST[$name]) ? $_POST[$name] : '';break;
			default:
				$value = isset($_GET[$name]) ? $_GET[$name] : '';break;
		}

		$value = htmlspecialchars(trim($value));

		return self::checkParam($value, $rule);
	}

	/**
	 * check the request param with some rule
	 * 
	 * @author v429
	 * @param  [type] $value param value
	 * @return [type]        [description]
	 */
	public static function checkParam($value, $rule = '')
	{
		$validate = new Validate($value, $rule);

		if (!$validate->check()) {
			$this->paramValidateErrorMsg[] = $validate->errorMsg;
		}

		return $value;
	}

	public static function getErrorMsg()
	{
		return $this->paramValidateErrorMsg;
	}

}