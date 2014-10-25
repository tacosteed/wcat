#!/usr/local/bin/php
<?php
ini_set('include_path', '/home/dmm/work/yano-tatsuya');
require_once ini_get('include_path') . '/yahoo/yahooApi.php';

$yahoo = new yahooApi();
$content = $yahoo->getProxyInfo();
print_r($content);
