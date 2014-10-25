<?php
/**
 * @file
 * @brief WEBスクレイピングクラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */

require_once ini_get('include_path') . '/lib/Api.php';

class googleApi extends Api
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
    const SEARCH_URL = "https://www.google.co.jp/search?q=%q&start=%no";
    #API_URL
    const SUGGEST_URL = "http://www.google.com/complete/search?hl=ja&q=%q&output=toolbar";
    #API_URL
    const PLAY_RANK_URL = "https://play.google.com/store/apps/category/%ccd/collection/%t";

    private $_type = array(
		0 => 'topselling_free',
		1 => 'topselling_paid'
    );

    private $header = array(
        "Accept:text/html,application/xhtml+xml,*/*",
        "Accept-Language:ja-JP",
    );

    private $_play_rank_condition = array(
       'ssl'         => true,
       'cookie_file' => 'cookie.txt',
       'type'        => 'post',
       'post_data'   => array(
           'hl'          => 'ja',
           'ipf'         => 1,
           'num'         => 60,
           'numChildren' => 0,
           'start'       => 0,
           'xhr'         => 1,
       )
    );

    function getSerps($keyword, $no = 0)
    {

        $url = self::SEARCH_URL;
        $url = preg_replace('/%q/', urlencode($keyword), $url);
        $url = preg_replace('/%no/', $no, $url);

        $this->setApiUrl($url);
        $this->setUserAgent(self::USER_AGENT);
        $this->setMaxRedirect(self::MAX_RETRY);
        $this->setCondition(array(
            'ssl' => true,
            'cookie_file' => 'cookie.txt'    
         ));
        $this->setReqHeader($this->header);
        $this->setResult($this->get());

    }

    function getSuggest($keyword)
    {

        $url = self::SUGGEST_URL;
        $url = preg_replace('/%q/', urlencode($keyword), $url);

        $this->setApiUrl($url);
        $this->setUserAgent(self::USER_AGENT);
        $this->setReqHeader($this->header);
        $this->get();
        $this->setResult($this->getXml());

    }

    function getPlayRank($categoryCd, $start, $type = 0)
    {

        $url = self::PLAY_RANK_URL;
        $url = preg_replace('/%ccd/', $categoryCd, $url);
        $url = preg_replace('/%t/', $this->_type[$type], $url);

        $this->setApiUrl($url);
        $this->setUserAgent(self::USER_AGENT);
        $this->setMaxRedirect(self::MAX_RETRY);
        $this->_play_rank_condition['post_data']['start'] = $start;
        $this->setCondition($this->_play_rank_condition);
        $this->setReqHeader($this->header);
        $this->setResult($this->get());

    }
}

