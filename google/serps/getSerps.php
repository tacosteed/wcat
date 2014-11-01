#!/usr/bin/php
<?php
require_once '../../lib/include.php';
require_once ini_get('include_path') . '/google/googleApi.php';
require_once ini_get('include_path') . '/google/googleGrep.php';
require_once ini_get('include_path') . '/lib/Common.php';

class getSerps
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
    #ワークディレクトリ
    const WORK_DIR = "work/";
 
    public function run($param)
    {

        $serp_count = 0;
        $rank = 1;

        $keyword = $param['keyword'];
        $resultFile = self::OUT_DIR. $param['parent_pid']. '/'.
            getmypid(). '_'. urlencode($keyword). '.tsv';

        $ofp = fopen($resultFile, 'w');

        #上限順位を取得しきるまでループ
        while ($param['max_rank'] > $rank) {

            print "get $keyword count $serp_count\n";
            $file = self::WORK_DIR. $param['parent_pid']. '/'. 
                getmypid(). '_'. urlencode($keyword). '_'. $serp_count. '.html';

            #Googleにアクセスし
            #一旦SERPをファイルOUT
            $google = new googleApi();
            $google->getSerps($keyword, $serp_count);
            $google->outFile($file);

            #出力したファイルをメモリ上に展開し
            #スクレイピング処理開始
            $serp = `/bin/cat $file`;
            $grep = new googleGrep();
            $serpList = $grep->serp($serp, 'all');

            #取得できた順位分ループ
            foreach ($serpList as $key => $val) {

                $out = array();
                array_push($out,
                    $keyword,
                    $rank,
                    $val['url'],
                    $val['title'],
                    $val['description']
                );
 
                #結果をファイルOUT
                fwrite($ofp, implode($out, self::SEPARATE). self::PARAGRAPH);
                $rank++;
            }

            $serp_count += $param['page_count'];
            #ランダムSLEEP
            sleep(mt_rand(self::MIN_RAND, self::MAX_RAND));

        }
        fclose ($ofp);
    }

}

$serps = new getSerps();
$param = array(
    'keyword'     => @$argv[1],
    'max_rank'    => @$argv[2],
    'page_count'  => @$argv[3],
    'parent_pid'  => @$argv[4],
);
$serps->run($param);
