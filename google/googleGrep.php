<?php
/**
 * @file
 * @brief Googleグレップクラス
 * @date 2014-05-20
 */

require_once ini_get('include_path') . '/lib/Api.php';

class googleGrep
{
    #正規表現
    const PREG = "<li class=\"g\">(.*?)<\/div><\/div><\/div><\/li>";
    #URL用正規表現
    const URL_PREG = "<h3 class=\"r\"><a href=\"(.*?)\"";
    #タイトル用正規表現
    const TITLE_PREG = "\)\">(.*?)<\/a><\/h3><div class=\"s\">";
    #DESCRIPTION用正規表現
    const DESCRIPTION_PREG = "<span class=\"st\">(.*?)<\/span>";
    #区切り文字

    public function serp($serp, $type = 'all')
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

