<?php
/**
 * @file
 * @brief WEBスクレイピングクラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */

require_once("Mail.php");
require_once("Mail/mime.php");
require_once("jphpmailer/jphpmailer.php");
mb_language("japanese");
mb_internal_encoding("UTF-8");

class getHatebuXml
{

    const OUT_FILE_PATH = 'out.tsv';
    const SEPARATE = "\t";
    const PARAGRAPH = "\n";

    const TO = "tatsuya0813steed@gmail.com";
    const SUBJECT = "技術メール";
    const FROM = "tatsuya0813steed@gmail.com";
    const FROM_NAME = "矢野 竜也";
    const ALTBODY = "テーブル";
 
    private $urlList = array(
        'count' => 'http://b.hatena.ne.jp/entrylist/it?sort=hot&threshold=&mode=rss',
#        'hot'   => 'http://b.hatena.ne.jp/entrylist/it?sort=count&threshold=&mode=rss'
    );

    public function getXml() {

        $ofp = fopen(self::OUT_FILE_PATH, 'w');

        foreach ($this->urlList as $key => $value) {
            $xml = simplexml_load_string(file_get_contents($this->urlList[$key]));
            foreach ($xml->item as $element) {
                $outs = array();
                array_push(
                    $outs,
                    mb_substr($element->title, 0, 30, 'UTF-8'),
                    $element->title,
                    "<a href=\"" . $element->link . "\">" . $element->link . "</a>",
                    mb_substr($element->description, 0, 50, 'UTF-8'),
                    $element->description
                );
                fwrite($ofp, implode(self::SEPARATE, $outs) . self::PARAGRAPH);
            }
        }
        fclose ($ofp);
    }

    public function changeFromTsvToHtmlTable($file_path) {

        $table = null;
        $header = "<table border=\"1\">";
        $footer = "</table>";

        if (!(is_readable($file_path) && ($fp = fopen($file_path, "rb")))) {
                die("Cannot Read InputFile");
        }

        $table = "<tr><td>";
        while (($row = fgets($fp)) !== false) {
            $row = preg_replace("/\t/i", "</td><td>", $row);
            $row = preg_replace("/\n/i", "</td></tr><tr><td>", $row);
            $table .= $row;
        }

        fclose ($fp);
        return $header. $table. $footer;
 
    }

    public function sendMail() {

        $mail = new JPHPMailer();
	$mail->CharSet = "iso-2022-jp";
        $mail->Encoding = "7bit";
         
        $mail->addTo(self::TO);
        $mail->setFrom(self::FROM, self::FROM_NAME);
        $mail->setSubject(mb_encode_mimeheader(self::SUBJECT, "UTF-8", "ISO-2022-JP"));
        $html = "<html>";
        $html .= "<head>";
        $html .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
        $html .= "</head>";
        $html .= $this->changeFromTsvToHtmlTable(self::OUT_FILE_PATH);
        $html .= "</html>";
        $mail->setHtmlBody($html);
        $mail->setAltBody(self::ALTBODY);
        if (!$mail->send()){
                echo("メールが送信できませんでした。エラー:".$mail->getErrorMessage());
        }
    }
} 

$xml = new getHatebuXml();
$xml->getXml();
$xml->sendMail();
#$xml->changeFromTsvToHtmlTable('out.tsv');
