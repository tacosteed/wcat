#!/usr/local/bin/php
<?php
ini_set('include_path', '/home/dmm/work/yano-tatsuya');
require_once ini_get('include_path') . '/yahoo/yahooApi.php';

$yahoo = new yahooApi();
$content = $yahoo->getMa("副業ばれない");
$xml = $yahoo->getResult();
print_r($xml);
#foreach ($xml->Result->Question as $key => $value) {
#    print_r($value);
#}
#print_r($yahoo->getResHeader());
