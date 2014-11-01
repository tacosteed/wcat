<?php
/**
 * @file
 * @brief WEBスクレイピングクラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */

require_once 'Data.php';

class Api extends Data
{

    #リダイレクトMAX数
    const MAX_RETRY = 10;
    #クロールする際のユーザエージェント
    private $_user_agent = 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)';
    #API_URL
    private $_api_url = null;
    #TYPE
    private $_type = 'get';
    #ID
    private $_id = null;
    #PW
    private $_pw = null;
    #MAXREDIRECT
    private $_maxredirect = 10;
    #PARAM
    private $_condition = array();
    #Requestヘッダー
    private $_req_header = array();
    #Responseヘッダー
    private $_res_header = array();
    #content
    private $_content = null;

    public function get()
    {

        print "get $this->_api_url\n";

        $base_condition = array(
            'url'         => $this->_api_url,
            'header'      => $this->_req_header,
            'maxredirect' => $this->_maxredirect,
            'useragent'   => $this->_user_agent,
            'type'        => $this->_type
        );

        $accessInfo = array_merge($base_condition, $this->_condition);
        $ret = $this->accessURL($accessInfo);
        $this->_content = mb_convert_encoding($ret['content'], 'UTF-8', 'auto');
        $this->_res_header = $ret['header'];
        return $this->_content;

    }

    /**
     * $accessInfo
     *   url => アクセスURL
     *   useragent => ユーザエージェント
     *   maxredirect => リダイレクト上限値
     *   type => 'get' or 'post'
     *   post_data => array ポスト情報
     *   cookie_file => クッキーファイル名
     *   header => array ヘッダー情報
     *   ssl => true or false
     */
    public function accessURL ($accessInfo) 
    {

        $ch = curl_init();

        //CURLに引数のオプション情報を渡す
        curl_setopt($ch, CURLOPT_URL, $accessInfo['url']);
        curl_setopt($ch, CURLOPT_USERAGENT, $accessInfo['useragent']);
        curl_setopt($ch, CURLOPT_MAXREDIRS, $accessInfo['maxredirect']);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        #POSTでのアクセス
        if ($accessInfo['type'] === 'post') {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $accessInfo['post_data']);
        }
        #cookie保持
        if (isset($accessInfo['cookie_file'])) {
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $accessInfo['cookie_file']);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $accessInfo['cookie_file']);
        }
        #ヘッダー偽装
        if (isset($accessInfo['header'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $accessInfo['header']);
        }
        #SSL
        if ($accessInfo['ssl'] == true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        //アクセス
        $content = curl_exec( $ch );

        //レスポンスヘッダー情報を抜き取る
        $info = curl_getinfo ($ch);
        $headers = split("\n", substr ($content, 0, $info["header_size"]));
        array_pop($headers);

        //HTMLの内容を抜き取る
        $ret['content'] = substr ($content, $info["header_size"]);
        curl_close($ch);

        //ヘッダー情報の解析
        $cnt = 0;
        foreach ($headers as $key => $value) {

            $value = trim($value);

            if (!$value) {
                $cnt++;
                continue;
            } elseif (preg_match("/^HTTP\/1\.1/",$value)) {
                preg_match("/ (.*?) /",$value,$retValue);
                $ret['header'][$cnt]["Status Code"] = trim($retValue[1]);
            } else {
                preg_match("/^(.*?):/",$value,$retKey);
                preg_match("/: (.*?)$/",$value,$retValue);
                $ret['header'][$cnt]["$retKey[1]"] = trim($retValue[1]);
            }

        }

        return $ret;

    }

    public function getXml()
    {
        return simplexml_load_string($this->_content);
    }

    public function outFile($file)
    {  

        $ofp = fopen($file, 'w');
        fwrite($ofp, $this->_content);
        fclose ($ofp);

    }

    public function setApiUrl($url)
    {
        $this->_api_url = $url;
        return;
    }

    public function setType($type)
    {
        $this->_type = $type;
        return;
    }

    public function setUserAgent($ua)
    {
        $this->_user_agent = $ua;
        return;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return;
    }

    public function setPw($pw)
    {
        $this->_pw = $pw;
        return;
    }

    public function setMaxRedirect($max)
    {
        $this->_maxredirect = $max;
        return;
    }

    public function setCondition($condition)
    {
        $this->_condition = $condition;
        return;
    }

    public function setReqHeader($header)
    {
        $this->_req_header = $header;
        return;
    }

    public function setResHeader($header)
    {
        $this->_res_header = $header;
        return;
    }

    public function getApiUrl()
    {
        return $this->_api_url;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getPw()
    {
        return $this->_pw;
    }

    public function getParam()
    {
        return $this->_param;
    }

    public function getReqHeader()
    {
        return $this->_req_header;
    }

    public function getResHeader()
    {
        return $this->_res_header;
    }

    public function getContent()
    {
        return $this->_content;
    }

}

