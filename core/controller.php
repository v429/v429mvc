<?php

/**
 * v429 controller engine
 */
class controller {

	/**
	 * @var $_registModles registed model files
	 */
	protected $_registModels;
	
	/**
	 * construct v429 controller !
	 */
	public function __construct()
	{
		$this->__loadModelFiles();
	}

	/**
	 * load all model file from model dir
	 */
	protected function __loadModelFiles() {
		$modelDir = dir('app/models');
 		while ($file = $modelDir->read()) {
 			if ($file != '..' && $file != '.') {
 				$modelName = substr($file, 0, (strlen($file) - 4));
 				$this->_registModels[strtolower($modelName)]['file'] = $file;
 				$this->_registModels[strtolower($modelName)]['name'] = $modelName;
 			}
 		}

 		$modelDir->close();
	}

	/**
	 * load a model obj
	 *
	 * @param $modelName model name 
	 */
	public function loadM($modelName) {
		if (!$modelInfo = $this->_registModels[$modelName]) {
			die('model '. $modelName . ' not found!');
		}

		include_once('app/models/' . $modelInfo['file']);

		$model = new $modelInfo['name']();

		return $model;
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