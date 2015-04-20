<?php
/**
 * @file
 * @brief 共通処理クラス
 * @date 2014-05-20
 */

require_once 'include.php';
require_once 'Processctrl.php';

class Common extends Processctrl
{

	private $_ini = array();
	private $_resultDir = null;
	private $_workDir = null;

	function __construct() {
		if (empty($this->_ini)) {
			$this->_ini = parse_ini_file("../include/conf.ini", true);
		}
	}

	function makeDir($id = null) {

		$id = $id ? $id : posix_getppid();
		$associative = debug_backtrace();
		$path = $this->_getDir($associative[0]["file"]);
		$this->_resultDir = $this->setResultDir($path). $id. '/';
		mkdir($this->_resultDir);
		$this->_workDir = $this->setWorkDir($path). $id. '/';
		mkdir($this->_workDir);

	}

	public function setWorkDir($path) {

		$path = $path. 'work/';

		if (!file_exists($path)) {
			system('/bin/mkdir -p '. $path);
		}

		return $path;

	}

	public function setResultDir($path) {

		$path = $path. 'result/';

		if (!file_exists($path)) {
			system('/bin/mkdir -p '. $path);
		}

		return $path;

	}

	private function _getDir($file) {

		$project = $this->_ini['project']['name'];
		$path = preg_replace("/^.*$project/", "", $file);
		$pos = strrpos($path, '/');
		$path = substr($path, 0, $pos + 1);

		return $this->_ini['path']['data']. $project. $path;

	}

	public function readInputFile($file, $separate = "\t") {

		$ret = array();

		//インプットファイルOPEN
		if (!(is_readable($file) && ($fp = fopen($file, "rb")))) {
			die("Cannot Read InputFile");
		}

		while (($row = fgets($fp)) !== false) {

			$column = split($separate, str_replace(array("\r\n","\r","\n"), '', $row));
			$ret[count($ret)] = $column;

		}

		fclose ($fp);
		return $ret;
	}

	public function getResultDir() {
		return $this->_resultDir;
	}

	public function getWorkDir() {
		return $this->_workDir;
	}

	public function getIni() {
		return $this->_ini;
	}

}

