<?php
//ライブラリ読み込み
require("./jphpmailer.php");

//言語設定、内部エンコーディングを指定する
mb_language("japanese");
mb_internal_encoding("EUC-JP");

//日本語HTMLメールを送る
$to = "kawamura@example.com"; //宛先
$subject = "例の件について"; //題名
$htmlbody = "<b>資料</b>を送ります"; //HTML本文
$altbody = "資料を送ります"; //代替テキスト本文
$from = "masaki@example.com"; //差出人
$fromname = "山本 正喜"; //差し出し人名


$mail = new JPHPMailer();

$mail->addTo($to);
$mail->setFrom($from,$fromname);
$mail->setSubject($subject);
$mail->setHtmlBody($htmlbody);
$mail->setAltBody($altbody);

if (!$mail->send()){
	die("メールが送信できませんでした。エラー:".$mail->getErrorMessage());
}