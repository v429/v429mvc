<?php

namespace Core\Request;

use Core\Request\Validate;

class Input
{
	/**
	 * @var [type]
	 */
	protected $validate;
	
	/**
	 * construct the input
	 * 
	 * @author v429
	 */
	public function __construct()
	{
		//validate
		$this->validate = new Validate();
	}

	/**
	 * get the request data
	 * 
	 * @author v429
	 * @param  [type] $name   data name
	 * @param  string $method request method
	 * @return [type]         [description]
	 */
	public function get($name, $rule = '', $method = '')
	{
		if (!$method) {
			$method = $_SERVER['REQUEST_METHOD'];
		}
		$method = strtoupper($method);

		//get input value
		switch ($method) {
			case 'GET':
				$value = isset($_GET[$name]) ? $_GET[$name] : '';break;
			case 'POST':
				$value = isset($_POST[$name]) ? $_POST[$name] : '';break;
			default:
				$value = isset($_GET[$name]) ? $_GET[$name] : '';break;
		}

		$value = htmlspecialchars(trim($value));
		//check param
		return $this->checkParam($name, $value, $rule);
	}

	/**
	 * check the request param with some rule
	 * 
	 * @author v429
	 * @param  [type] $value param value
	 * @return [type]        [description]
	 */
	public function checkParam($name ,$value, $rule = '')
	{
		$this->validate->check($value, $rule, $name);

		return $value;
	}

	/**
	 * get all validate error massage
	 * 
	 * @author v429
	 */
	public function getErrorMsg()
	{
		return $this->validate->errorMsg;
	}

}