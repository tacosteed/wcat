#!/usr/bin/php
<?php
require_once '../lib/include.php';
require_once ini_get('include_path') . '/scraper/webScraper.php';
require_once ini_get('include_path') . '/lib/Common.php';

class getTdk extends Common
{

	#区切り文字
	const SEPARATE = "\t";
	#改行文字
	const PARAGRAPH = "\n";

	public function __construct(){
		parent::__construct();
	}

	public function run($param)
	{

		$inputFile = $param['input_file'];
		$this->makeDir($param['parent_pid']);
		$param['url_row'] = isset($param['url_row']) ? $param['url_row'] : 0;

		$resultFile = $this->getResultDir().
			getmypid(). '.tsv';
		$list = $this->readInputFile($inputFile);
		$ofp = fopen($resultFile, 'w');

		foreach ($list as $key => $val) {

			$out = array();
			$url = $val[$param['url_row']];
			$base_keyword = $val[$param['keyword_row']];

			$scraper = new webScraper();
			$scraper->access($url);

			// タイトル
			$title = $scraper->getTitle();

			if ($title) {
				fwrite($ofp, $title. self::SEPARATE. 'title'.
					self::SEPARATE. $url. self::SEPARATE. $base_keyword. self::PARAGRAPH);
			}

			// ディスクリプション
			$description = $scraper->getDescription();

			if ($description) {
				fwrite($ofp, $description. self::SEPARATE. 'description'.
					self::SEPARATE. $url. self::SEPARATE. $base_keyword. self::PARAGRAPH);
			}

			// キーワード
			$keyword = $scraper->getKeyword();

			if ($keyword) {
				fwrite($ofp, $keyword. self::SEPARATE. 'keyword'.
					self::SEPARATE. $url. self::SEPARATE. $base_keyword. self::PARAGRAPH);
			}

			// h1
			$h1s = $scraper->getH1();

			if (is_array($h1s)) {
				foreach ($h1s as $h1) {
					if ($h1) {
						fwrite($ofp, $h1. self::SEPARATE. 'h1'.
							self::SEPARATE. $url. self::SEPARATE. $base_keyword. self::PARAGRAPH);
					}
				}
			}

			// p
			$ps = $scraper->getP();

			if (is_array($ps)) {
				foreach ($ps as $p) {
					if ($p) {
						fwrite($ofp, $p. self::SEPARATE. 'p'.
							self::SEPARATE. $url. self::SEPARATE. $base_keyword. self::PARAGRAPH);
					}
				}
			}

			sleep(0);

		}

		fclose ($ofp);

	}
}

$serps = new getTdk();
$param = array(
	'input_file'	=> @$argv[1],
	'parent_pid'	=> @$argv[2],
	'url_row'		=> @$argv[3],
	'keyword_row'	=> @$argv[4],
);
$serps->run($param);
