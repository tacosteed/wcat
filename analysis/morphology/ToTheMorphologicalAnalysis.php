#!/usr/bin/php
<?php
require_once '../lib/include.php';
require_once 'Morphology.php';
require_once ini_get('include_path') . '/lib/Common.php';

class ToTheMorphologicalAnalysis extends Common
{

    public function __construct(){
    	parent::__construct();
    }

    public function run($param)
    {

    	$file = $this->readInputFile($param['in_file_name']);
    	$morphology = new Morphology();

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

    	foreach ($file as $key => $sentence) {
			$morphology->setSentence($sentence[0]);
			$morphology->countWord('noun');
    	}

		$ret = $morphology->getWord();
		print_r($ret);

    }

}

$analysis = new ToTheMorphologicalAnalysis();
$param = array(
	'in_file_name'		=> @$argv[1],
	'out_file_name'		=> @$argv[1],
	'parent_pid'		=> @$argv[2],
);
$analysis->run($param);
