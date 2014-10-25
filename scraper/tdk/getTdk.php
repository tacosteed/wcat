#!/usr/local/bin/php
<?php
require_once '../../lib/include.php';
require_once ini_get('include_path') . '/scraper/webScraper.php';
require_once ini_get('include_path') . '/lib/Common.php';

class getTdk
{

    #区切り文字
    const SEPARATE = "\t";
    #改行文字
    const PARAGRAPH = "\n";
    #出力ディレクトリ
    const OUT_DIR = "result/";
    #ワークディレクトリ
    const WORK_DIR = "work/";
 
    public function run($param)
    {

        $inputFile = $param['input_file'];
        $resultFile = self::OUT_DIR. $param['parent_pid']. '/'.
            getmypid(). '.tsv';
        $list = Common::readInputFile($inputFile);
        $ofp = fopen($resultFile, 'w');

        foreach ($list as $key => $val) {

            $out = array();
            $url = $val[0];
            #GoogleApiにアクセスし
            $scraper = new webScraper();
            $scraper->access($url);

            array_push($out, $url);
            array_push($out, $scraper->getTitle());
            array_push($out, $scraper->getDescription());
            array_push($out, $scraper->getKeyword());
            array_push($out, count($scraper->getH1()));
            array_push($out, implode('|||', $scraper->getH1()));

            fwrite($ofp, implode($out, self::SEPARATE). self::PARAGRAPH);
            sleep(1);

        }

       fclose ($ofp);

   }
}

$serps = new getTdk();
$param = array(
    'input_file'  => @$argv[1],
    'parent_pid'  => @$argv[2],
);
$serps->run($param);
