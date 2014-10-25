<?php
/**
 * @file
 * @brief WEBスクレイピングクラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */
require_once ini_get('include_path') . '/lib/Api.php';
require_once 'XML/RPC.php';

class hatebuApi extends Api
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
    #はてぶドメイン
    const HATEBU_DOMAIN = "d.hatena.ne.jp";
    #連想メソッド
    const RENSO_METHOD = "hatena.getSimilarWord";

    private $_user_agent = 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)';

    private $_header = array(
        "Accept:text/html,application/xhtml+xml,*/*",
        "Accept-Language:ja-JP",
    );

    public function accessByXmlRpc($keyword)
    {

        $client = new XML_RPC_client('/xmlrpc', self::HATEBU_DOMAIN);
        $params = new XML_RPC_Value(
            array(
                'wordlist' => new XML_RPC_Value($keyword)
            ),
            'struct'
        );
        $message = new XML_RPC_Message(self::RENSO_METHOD, array($params));
        $response = $client->send($message);
        print_r($response);

    }

    public function setUserAgent($ua)
    {
        $this->_user_agent = $ua;
    }

    public function setHeader($header)
    {
        $this->_header = $header;
    }

}
