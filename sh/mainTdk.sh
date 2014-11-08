#!/bin/sh
source ../include/common.conf
source ../include/tdk.conf
source ../lib/common.sh

IN_FILE=$1
RESULT_DIR=/var/local/data/wcat/google/serps/result/$$
TDK_PATH=$COMMON_PATH/$PATH

echo_start "mainTdk" $$

$TDK_PATH/getTdk.php $IN_FILE $$ $2

echo_end "mainTdk" $$
