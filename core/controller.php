<?php

class controller {

	protected $_registModels;
	
	public function __construct()
	{
		$this->__loadModelFiles();
	}

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

	public function loadM($modelName) {
		if (!$modelInfo = $this->_registModels[$modelName]) {
			die('model '. $modelName . ' not found!');
		}

		include_once('app/models/' . $modelInfo['file']);

		$model = new $modelInfo['name']();

		return $model;
	}

	public function display($path, $data) {
		$view = new View();

		$view->display($path, $data);
	}


}