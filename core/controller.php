<?php
namespace Core;

/**
 * v429 controller engine
 */
class controller 
{

	
	/**
	 * construct v429 controller !
	 */
	public function __construct()
	{

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