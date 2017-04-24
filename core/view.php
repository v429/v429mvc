<?php

/**
 * view engine for v429
 * start view
 */

class View {
	
	/**
	 * @var view file dir
	 */
	public $viewDir = '';

	/**
	 * @var view tmp file dir
 	 */
 	public $viewCacheDir = "";

 	/**
 	 * construct view engine
 	 *
 	 * @param dir view dir define
 	 */
	public function __construct($dir = 'app/views/') {
		$this->viewDir = $dir;

		$this->viewCacheDir = "cache/views/";
	}

	/**
	 * display a view 
	 *
	 * @param $filePath view file path 
	 * @param $data the data to file
	 */
	public function display($filePath , $data = []) {
		foreach ($data as $key => $value) {
			$$key = $value;
		}

		//make tpl path
		$tplFile = $this->_makeTpl($filePath);

		include($this->viewCacheDir . $tplFile);

		exit;
	}

	/**
	 * make tpl in path
	 *
	 * @param $filePath
	 */
	protected function _makeTpl ($filePath) {
		$tplFIleName = md5 (time() . mt_rand(0, 500)) . '-tpl.php';

		$tplFile = fopen($this->viewCacheDir . $tplFIleName, "a") or die("Unable to open file!");

		//load the view file content
		$content = $this->_loadViewFile($filePath);

		//replace the code of view file
		$content = $this->_replaceCode($content);

		fwrite($tplFile, $content);
		fclose($tplFile);

		return $tplFIleName;
	}

	/**
	 * replace key world from view file content
	 *
	 * @param $content view file content
	 */
	protected function _replaceCode($content) {
		$content = $this->_replaceParam($content);

		return $content;
	}

	/**
	 * replace normal param from view file content
	 *
	 * @param $content view file content
	 */
	protected function _replaceParam($content) {
		$content = str_replace('{{', '<?php echo ', $content);

		$content = str_replace("}}", "; ?>", $content);

		return $content;
	}

	/**
	 * load the view file return content
	 *
	 * @param $fileName view file path
	 */
	protected function _loadViewFile($fileName = '') {
		$path = $this->viewDir . $fileName . '.php';
		if (!file_exists($path)) {
			die('FILE : ' . $path . 'NOT EXIST');
		}

		$content = file_get_contents($path);

		return $content;
	}


}