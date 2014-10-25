<?php
/**
 * @file
 * @brief WEBスクレイピングクラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */
require_once ini_get('include_path') . '/lib/Api.php';
require_once 'goutte.phar';
use Goutte\Client;

class webScraper extends Api
{

   #リダイレクトMAX数
    const MAX_REDIRECT = 10;
    #区切り文字
    const SEPARATE = "\t";
    #改行文字
    const PARAGRAPH = "\n";
    #OK文字
    const OK = "OK";
    #NG文字
    const NG = "NG";
    #インプットファイル区切り文字
    const INPUT_SEPARATE = ",";

    private $_user_agent = 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)';

    private $_header = array(
        "Accept:text/html,application/xhtml+xml,*/*",
        "Accept-Language:ja-JP",
    );

    /**
     * サイトにアクセス
     */
    public function access($url)
    {

        $this->setApiUrl($url);
        $this->setUserAgent($this->_user_agent);
        $this->setMaxRedirect(self::MAX_RETRY);
        $this->setCondition(array(
            'ssl' => true,
            'cookie_file' => 'cookie.txt'    
         ));
        $this->setReqHeader($this->_header);
        $this->setResult($this->get());

    }

    public function setUserAgent($ua)
    {
        $this->_user_agent = $ua;
    }

    public function setHeader($header)
    {
        $this->_header = $header;
    }

    public function getHead()
    {
        preg_match('/<head(.*?)<\/head>/si', $this->getContent(), $head);
        return $this->scraperTrim($head[1]);
    }

    public function getTitle()
    {
        preg_match('/<title>(.*?)<\/title>/si', $this->getContent(), $title);
        return $this->scraperTrim($title[1]);
    }

    public function getDescription()
    {
        preg_match('/<meta name=\"description\"(.*?)>/si', $this->getContent(), $description);
        preg_match('/content=\"(.*?)\"/si', $description[1], $text);

        return $this->scraperTrim($text[1]);
    }

    public function getKeyword()
    {
        preg_match('/<meta name=\"keywords\"(.*?)>/si', $this->getContent(), $keyword);
        preg_match('/content=\"(.*?)\"/si', $keyword[1], $text);

        return $this->scraperTrim($text[1]);
    }

    public function getH1()
    {
        preg_match_all('/<h1(.*?)<\/h1>/si', $this->getContent(), $h1);
        for ($i = 0 ; $i < count($h1[1]); $i++) {
            preg_match('/>(.*?)$/si', $h1[1][$i], $text);
            $h1[1][$i] = $this->scraperTrim($text[1]);
        }

        return $h1[1];
    }

    public function scraperTrim($str)
    {

        $str = html_entity_decode(strip_tags($str));
        $str = preg_replace('/[\x00-\x1f\x7f]/', '', $str);
        $str = preg_replace('@\p{Cc}@u', '', $str);
        $str = str_replace(array("\r\n","\r","\n"), '', $str);

        return $str;
    }

    public function getStatus()
    {

        $status = array();
        $header = $this->getResHeader();
        //レスポンスヘッダー情報の解析
        for($i = 0; $i < count($header); $i++){

            //ステータスコードを再帰的に読み込み
            array_push($status, $header[$i]['Status Code']);

        }
        return $status;

    }

    public function getLocation()
    {

        $location = array();
        $header = $this->getResHeader();
        //レスポンスヘッダー情報の解析
        for($i = 0; $i < count($header); $i++){

            //locationを再帰的に読み込み
            if (isset($header[$i]['Location'])) {
                array_push($location, $header[$i]['Location']);
            }

        }
        return $location;

    }

    public function getRedirectInfo()
    {

        return  array(
            'status'   => $this->getStatus(),
            'location' => $this->getLocation(),
        );

    }
}
