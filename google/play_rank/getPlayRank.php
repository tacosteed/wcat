#!/usr/local/bin/php
<?php
require_once '../../lib/include.php';
require_once ini_get('include_path') . '/google/googleApi.php';
require_once ini_get('include_path') . '/lib/Common.php';

class getPlayRank
{

    #ランダムスリープ用
    const MIN_RAND = 1;
    #ランダムスリープ用
    const MAX_RAND = 10;
    #正規表現
    const PREG = "<li class=\"g\">(.*?)<\/div><\/div><\/div><\/li>";
    #URL用正規表現
    const URL_PREG = "<h3 class=\"r\"><a href=\"(.*?)\"";
    #タイトル用正規表現
    const TITLE_PREG = "\)\">(.*?)<\/a><\/h3><div class=\"s\">";
    #DESCRIPTION用正規表現
    const DESCRIPTION_PREG = "<span class=\"st\">(.*?)<\/span>";
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

        $start = 0;
        $rank = 1;

        $category_cd = $param['category_cd'];
        $type = $param['type'];
        $resultFile = self::OUT_DIR. $param['parent_pid']. '/'.
            getmypid(). '_'. urlencode($category_cd). '.tsv';

        $ofp = fopen($resultFile, 'w');

        #上限順位を取得しきるまでループ
        while ($param['max_rank'] > $rank) {

            print "get $category_cd type $type count $start\n";
            $file = self::WORK_DIR. $param['parent_pid']. '/'. 
                getmypid(). '_'. $category_cd. '_'. $start. '.html';

            #Googleにアクセスし
            #一旦SERPをファイルOUT
            $google = new googleApi();
            $google->getPlayRank($category_cd, 10060, $type);
            $google->outFile($file);
die;

            #出力したファイルをメモリ上に展開し
            #スクレイピング処理開始
            $serp = `/bin/cat $file`;
            $rankList = $this->grepRank($serp, 'all');
            #取得できた順位分ループ
            foreach ($rankList as $key => $val) {

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

    public function grepSerp($serp, $type = 'all')
    {

        $ret = array();
        $preg             = self::PREG;
        $url_preg         = self::URL_PREG;
        $title_preg       = self::TITLE_PREG;
        $description_preg = self::DESCRIPTION_PREG;
        #SERPからリストタグで順位情報を抽出
        preg_match_all("/$preg/", $serp, $matches, PREG_PATTERN_ORDER);

        foreach ($matches[0] as $key => $part) {

            if ($type === 'rank' && preg_match('/<a class=\"fl nobr\"/', $part)) {
                continue;
            } else if ($type === 'place' && !preg_match('/<a class=\"fl nobr\"/', $part)) {
                continue;
            }
            #URL.TITLE,DESCRIPTIONを抽出
            preg_match("/$url_preg/", $part, $url);
            preg_match("/$title_preg/", $part, $title);
            preg_match("/$description_preg/", $part, $description);

            #余計なHTMLタグを除去
            $rank['url'] = strip_tags($url[1]);
            $rank['title'] = strip_tags($title[1]);
            $rank['description'] = strip_tags($description[1]);

            $ret[count($ret)] = $rank;
        }

        return $ret;

    }
}

$play = new getPlayRank();
$param = array(
    'category_cd' => @$argv[1],
    'type'        => @$argv[2],
    'max_rank'    => @$argv[3],
    'parent_pid'  => @$argv[4],
);
$play->run($param);
