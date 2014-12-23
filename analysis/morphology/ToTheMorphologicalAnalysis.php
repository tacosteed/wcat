#!/usr/bin/php
<?php
require_once '../lib/include.php';
require_once 'Morphology.php';
require_once ini_get('include_path') . '/lib/Common.php';

class ToTheMorphologicalAnalysis extends Common
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

    	$file = $this->readInputFile($param['in_file_name']);
		$this->makeDir($param['parent_pid']);
    	$morphology = new Morphology();
		$resultFile = $this->getResultDir().
			getmypid(). '.tsv';

//		$pctrl = Processctrl::getInstace();
//		$pctrl->setMaxProcess(4);
//		$pctrl->setTimeout(3);

//    	foreach ($file as $key => $sentence) {
//    		$pctrl->addArgs(array($sentence));
//    	}

//		$pctrl->run_all(function($data) {
//			declare(ticks = 1) {
//				$morphology->setSentence($data);
//				$morphology->countWord('noun');
//			}
//		});

		$keyword = null;
    	foreach ($file as $key => $sentence) {
			if ($keyword != $sentence[$param['keyword_row']] && $key != 0) {
				$ret = $morphology->getWord('arsort');
				$this->outFile($ret, $resultFile, $keyword);
				$morphology->clearWord();
			}
			$keyword = $sentence[$param['keyword_row']];
			$morphology->setSentence($sentence[$param['sentence_row']]);
			$morphology->countWord('noun');
		}

    }

    public function outFile($ret, $resultFile, $keyword)
    {

		$ofp = fopen($resultFile, 'a');

    	foreach ($ret as $key => $count) {
			fwrite($ofp, $keyword. self::SEPARATE. $key. self::SEPARATE. $count. self::PARAGRAPH);
    	}

    }

}

$analysis = new ToTheMorphologicalAnalysis();
$param = array(
	'in_file_name'		=> @$argv[1],
	'parent_pid'		=> @$argv[2],
	'sentence_row'		=> @$argv[3],
	'keyword_row'		=> @$argv[4],
);
$analysis->run($param);
