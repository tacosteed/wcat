<?php
/**
 * @file
 * @brief WEBスクレイピングクラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */

require_once ini_get('include_path') . '/lib/Api.php';

class itunesApi extends Api
{

    #クロールする際のユーザエージェント
    const USER_AGENT = 'User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)';
    #リダイレクトMAX数
    const MAX_RETRY = 10;
    #区切り文字
    const SEPARATE = "\t";
    #改行文字
    const PARAGRAPH = "\n";
    #インプットファイル区切り文字
    const INPUT_SEPARATE = ",";
    #API_URL
    const RANK_URL = "https://itunes.apple.com/jp/rss/%t/limit=%l/genre=%ccd/xml";

    private $_type = array(
		0 => 'topfreeapplications',
		1 => 'toppaidapplications',
		2 => 'topgrossingapplications',
		3 => 'topfreeipadapplications',
		4 => 'toppaidipadapplications',
		5 => 'topgrossingipadapplications',
		6 => 'newapplications',
		7 => 'newfreeapplications',
		8 => 'newpaidapplications'
    );

    private $header = array(
        "Accept:text/html,application/xhtml+xml,*/*",
        "Accept-Language:ja-JP",
    );

    private $_rank_condition = array(
       'type'        => 'get',
       'ssl'         => true,
       'cookie_file' => 'cookie.txt',
    );

    function getRankXml($categoryCd, $limit, $type = 0)
    {

        $url = self::RANK_URL;
        $url = preg_replace('/%ccd/', $categoryCd, $url);
        $url = preg_replace('/%t/', $this->_type[$type], $url);
        $url = preg_replace('/%l/', $limit, $url);

        $this->setApiUrl($url);
        $this->setUserAgent(self::USER_AGENT);
        $this->setMaxRedirect(self::MAX_RETRY);
        $this->setCondition($this->_rank_condition);
        $this->setReqHeader($this->header);
        $this->setResult($this->get());

    }
}

