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
		$tplFIleName = $filePath . '-tpl.php';

		$tplFile = fopen($this->viewCacheDir . $tplFIleName, "w") or die("Unable to open file!");

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

		//$content = $this->_replaceIf($content);

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

	protected function _replaceIf($content) {
		$result = $replace = '';

		$patend = '/(\[if\]\()(.*)(\))(\s??)(.*)(\[elseif\]\()(.*)(\))(\s??)(.*)\[endif\]/';

		preg_match_all($patend, $content, $result);

		print_r($result);exit;
		if ($result[0] && $result[1]) {
			$replace = "<?php if" . $result[2][0] . "){ ?>";
		}
		$re = preg_replace('/(\[if\])(.*)(\))/', $replace, $content);

		$re = str_replace('[else]', '<?php } else { ?>', $re);

		return $re;
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