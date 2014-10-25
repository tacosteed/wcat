<?php
/**
 * @file
 * @brief WEBスクレイピングクラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */

require_once ini_get('include_path') . '/lib/Api.php';

class yahooApi extends Api
{

    #クロールする際のユーザエージェント
    const USER_AGENT = 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)';
    #リダイレクトMAX数
    const MAX_RETRY = 10;
    #区切り文字
    const SEPARATE = "\t";
    #改行文字
    const PARAGRAPH = "\n";
    #インプットファイル区切り文字
    const INPUT_SEPARATE = ",";
    #API_URL
    const CHIEBUKURO_API_URL = "http://chiebukuro.yahooapis.jp/Chiebukuro/V1/questionSearch?appid=%id&query=%q";
    #API_URL
    const MA_API_URL = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=%id&results=ma,uniq&uniq_filter=9%7C10&sentence=%q";
    #PROXY
    const PROXY_URL = "http://taruo.net/e/";
    #ID
    const ID = "dj0zaiZpPUYxRVlIZ2Rzc2VrayZkPVlXazlabEZTWkUxTU5UZ21jR285TUEtLSZzPWNvbnN1bWVyc2VjcmV0Jng9ZDY-";

    private $header = array(
        "HTTP/1.0",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        "Accept-Encoding:gzip ,deflate",
        "Accept-Language:ja,en-us;q=0.7,en;q=0.3",
        "Connection:keep-alive",
        "User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:26.0) Gecko/20100101 Firefox/26.0"
    );

    function getChiebukuro($keyword)
    {

        $url = self::CHIEBUKURO_API_URL;
        $url = preg_replace('/%id/', self::ID, $url);
        $url = preg_replace('/%q/', urlencode($keyword), $url);

        $this->setApiUrl($url);
        $this->setUserAgent(self::USER_AGENT);
        $this->setMaxRedirect(self::MAX_RETRY);

        $this->get();
        $this->setResult($this->getXml());

    }

    function getMa($keyword)
    {

        $url = self::MA_API_URL;
        $url = preg_replace('/%id/', self::ID, $url);
        $url = preg_replace('/%q/', urlencode($keyword), $url);

        $this->setApiUrl($url);
        $this->setUserAgent(self::USER_AGENT);
        $this->setMaxRedirect(self::MAX_RETRY);

        $this->get();
        $this->setResult($this->getXml());

    }

    function getProxyInfo()
    {

        $url = self::PROXY_URL;

        $this->setApiUrl($url);
        $this->setUserAgent(self::USER_AGENT);
        $this->setMaxRedirect(self::MAX_RETRY);
        $this->setReqHeader($this->header);

        return $this->get();

    }
}

