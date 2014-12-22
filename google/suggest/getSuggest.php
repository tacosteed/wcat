#!/usr/bin/php
<?php
require_once '../lib/include.php';
require_once ini_get('include_path') . '/google/googleApi.php';
require_once ini_get('include_path') . '/lib/Common.php';

class getSuggest extends Common
{

	#ランダムスリープ用
	const MIN_RAND = 1;
	#ランダムスリープ用
	const MAX_RAND = 10;
	#区切り文字
	const SEPARATE = "\t";
	#改行文字
	const PARAGRAPH = "\n";
	#出力ディレクトリ
	const OUT_DIR = "result/";

	public function __construct(){
		parent::__construct();
	}

	public function run($param)
	{

		$keywords = array($param['keyword']);
		$this->makeDir($param['parent_pid']);
		$resultFile = $this->getResultDir().
			getmypid(). '_'. urlencode($param['keyword']). '.tsv';

		$count = 1;
		# 設定された階層数まで再帰的に関連キーワードを取得
		while ($count < $param['max_count']) {

			$result = array();

			foreach ($keywords as $key => $keyword) {

				$query = split(self::SEPARATE, $keyword);
				print "get $keyword\n";
				#GoogleApiにアクセスし
				$google = new googleApi();
				$google->getSuggest($query[count($query) - 1]);
				$xml = $google->getResult();

				#XMLをパース
				foreach ($xml->CompleteSuggestion as $key => $val) {
					array_push($result, $keyword. self::SEPARATE. $val->suggestion['data']);
				}

				#ランダムSLEEP
				sleep(mt_rand(self::MIN_RAND, self::MAX_RAND));
			}
			$keywords = $result;
			$count++;
			print "count $count\n";
		}

		#結果をファイルOUT
		$ofp = fopen($resultFile, 'w');
		foreach ($keywords as $key => $keyword) {
			fwrite($ofp, $keyword. self::PARAGRAPH);
		}
		fclose ($ofp);

		#ランダムSLEEP
		sleep(mt_rand(self::MIN_RAND, self::MAX_RAND));
	}
}

$serps = new getSuggest();
$param = array(
	'keyword'		=> @$argv[1],
	'max_count'		=> @$argv[2],
	'parent_pid'	=> @$argv[3],
);
$serps->run($param);
