<?php

/**
 * Request object for routes and controllers
 */

class Request {
	/**
 	 * @var $_routes  all route registed
 	 */
 	protected $_routes = [];

 	/**
 	 * @var $_regestUris  all uri registed
 	 */
 	protected $_regestUris = [];

	/**
 	 * @var $_currentUri  CURRENT URL
 	 */
 	protected $_currentUri = '';

 	/**
 	 * @var $_currentAction  CURRENT CONTROLLER ACTION
 	 */
 	protected $_currentAction = "";

 	 /**
 	 * @var $_currentController  CURRENT CONTROLLER
 	 */
 	protected $_currentController = "";

 	/**
 	 * @var $_registControllers
 	 */
 	protected $_registControllers;

 	/**
 	 * construct the v429!!
 	 */
 	public function __construct()
 	{
 		//register routes
 		$this->_initRoutes();

 		//get current url,controller and action
 		$this->_currentUri = $this->_splitCurUri();

 		//die without controller regist
 		if (!$this->_currentController) {
 			die('ROUTE:controller not found!');
 		}

 		$this->_registControllerFIles();
 	}

 	/**
 	 * init routes
 	 */
 	protected function _initRoutes() 
 	{
 		$routes = include_once('app/route.php');

 		foreach ($routes as $key => $route) {
 			$this->_routes[$key]['url']             = $key;
 			$this->_routes[$key]['controller'] = strtolower($route[0]);
 			$this->_routes[$key]['action']       = isset($route[1]) ? strtolower($route[1]) : '';

 			$this->_regestUris[] = strtolower($key);
 		}
 	}

 	/**
 	 * init current url, controller , action
 	 */
 	protected function _splitCurUri()
 	{
 		$curUrl = $_SERVER['REQUEST_URI'];

 		$uris = explode('?', $curUrl);

 		$this->_currentControllerAndAction($uris[0]);
 	}

 	/**
 	 * get current controller and action
 	 */
 	protected function _currentControllerAndAction($uri) {
 		$uri = explode('/', $uri);

 		foreach ($uri as $k => $u) {
 			if (!$u) {
 				unset($uri[$k]);
 			}
 		}
 		//rebuild uri
 		$uri = $uri ? implode('/', $uri) : '/';

 		//uri is in regest uris
 		if (in_array($uri, $this->_regestUris)) {
 			$this->_currentController  = strtolower($this->_routes[$uri]['controller']);

 			$this->_currentAction =  $this->_routes[$uri]['action'] ? strtolower($this->_routes[$uri]['action']) : 'index';
 		} else {
 			//current action
 			$uris = explode('/', $uri);

 			$this->_currentAction = $uris[(count($uris) - 1)];

 			//current controller
 			$controllerUriArr = array_slice($uris, 0, (count($uris) - 1));

 			$controllerUir = implode('/', $controllerUriArr);

 			$this->_currentController = strtolower($this->_routes[$controllerUir]['controller']);
 		}
 	}

 	/**
 	 * regist all the controller file
 	 */
 	protected function _registControllerFIles() {
 		$controllerDir = dir('app/controllers');
 		while ($file = $controllerDir->read()) {
 			if ($file != '..' && $file != '.') {
 				$controllerName = substr($file, 0, (strlen($file) - 4));
 				$this->_registControllers[strtolower($controllerName)]['file'] = $file;
 				$this->_registControllers[strtolower($controllerName)]['name'] = $controllerName;
 			}
 		}

 		$controllerDir->close();
 	}

 	/**
 	 * produce a new controller
 	 */
 	public function factoryController() {
 		$request = $this->_registControllers[$this->_currentController];

 		if (!$request) {
 			die('controller not found!');
 		}

 		include_once('app/controllers/' . $request['file']);

 		$controller = new $request['name']();

 		if (!$controller) {
 			die('controller'. $this->_currentController . ' not found!');
 		}

 		$methods = get_class_methods($controller);

 		if (!in_array($this->_currentAction, $methods)) {
 			die('method '. $this->_currentAction . ' not found!');
 		}

 		return $controller;
 	}

 	/**
 	 * get current controller
 	 */
 	 public function getCurrentController() {
 		return $this->_currentController;
 	}

 	/**
 	 * get current action
 	 */ 
 	public function getCurrentAction() {
 		return $this->_currentAction;
 	}
}