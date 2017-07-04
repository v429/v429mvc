<?php

namespace Core;

use Core\Request\Request;

/**
 * start app v429!
 */

 class v429 extends Request
 {

 	/**
 	 * get new request start run!
 	 */
 	public function run()
 	{
 		$controller = $this->factoryController();

 		$action = $this->_currentAction;

 		$controller->$action();
 	}


 }