#!/usr/local/bin/php
<?php
require_once '../../lib/include.php';
require_once ini_get('include_path') . '/scraper/webScraper.php';
require_once ini_get('include_path') . '/lib/Common.php';

class checkRedirect
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
            $redirect = $scraper->getRedirectInfo();

            array_push($out, $url);
            array_push($out, implode($redirect['status'], ','));
            array_push($out, implode($redirect['location'], ','));

            fwrite($ofp, implode($out, self::SEPARATE). self::PARAGRAPH);
            sleep(1);

        }

       fclose ($ofp);

   }
}

$redirect = new checkRedirect();
$param = array(
    'input_file'  => @$argv[1],
    'parent_pid'  => @$argv[2],
);
$redirect->run($param);
