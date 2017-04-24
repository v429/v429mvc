<?php

class View {
	
	public $viewDir = '';

	public $viewCacheDir = "";

	public function __construct($dir = 'app/views/') {
		$this->viewDir = $dir;

		$this->viewCacheDir = "cache/views/";
	}

	public function display($filePath , $data = []) {
		foreach ($data as $key => $value) {
			$$key = $value;
		}

		$tplFile = $this->_makeTpl($filePath);

		include($this->viewCacheDir . $tplFile);

		exit;
	}

	protected function _makeTpl ($filePath) {
		$tplFIleName = md5 (time() . mt_rand(0, 500)) . '-tpl.php';

		$tplFile = fopen($this->viewCacheDir . $tplFIleName, "a") or die("Unable to open file!");

		$content = $this->_loadViewFile($filePath);
		$content = $this->_replaceCode($content);

		fwrite($tplFile, $content);
		fclose($tplFile);

		return $tplFIleName;
	}

	protected function _replaceCode($content) {
		$content = $this->_replaceParam($content);

		return $content;
	}

	protected function _replaceParam($content) {
		$content = str_replace('{{', '<?php echo ', $content);

		$content = str_replace("}}", "; ?>", $content);

		return $content;
	}

	protected function _loadViewFile($fileName = '') {
		$path = $this->viewDir . $fileName . '.php';
		if (!file_exists($path)) {
			die('FILE : ' . $path . 'NOT EXIST');
		}

		$content = file_get_contents($path);

		return $content;
	}


}