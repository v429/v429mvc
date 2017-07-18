<?php
namespace Core\Request;

use Core\Request\Input;

/**
 * v429 controller engine
 */
class Controller 
{

	protected $input;
	
	/**
	 * construct v429 controller !
	 */
	public function __construct()
	{
		$this->input = new Input();
	}


	/**
	 * display a view
	 *
	 * @param $path view file path
	 * @param $data view datas
	 */
	public function display($path, $data) {
		$view = new View();

		$view->display($path, $data);
	}


}