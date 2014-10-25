#!/usr/local/bin/php
<?php
require_once '../../lib/include.php';
require_once ini_get('include_path') . '/hatebu/hatebuApi.php';
require_once ini_get('include_path') . '/lib/Common.php';

class getRenso
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
 
    public function run($param)
    {

        $keywords = array($param['keyword']);
        $resultFile = self::OUT_DIR. $param['parent_pid']. '/'.
            getmypid(). '_'. urlencode($keyword). '.tsv';

        #Googleにアクセスし
        #一旦SERPをファイルOUT
        $renso = new hatebuApi();
        $renso->accessByXmlRpc($keyword);
        $renso->outFile($file);


    }
}

$renso = new getRenso();
$param = array(
    'keyword'     => @$argv[1],
    'parent_pid'  => @$argv[2],
);
$renso->run($param);
