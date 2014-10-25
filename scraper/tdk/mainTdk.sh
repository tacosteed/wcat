#!/bin/sh
source ../../conf/common.conf
source ../../conf/tdk.conf
source ../../lib/common.sh

IN_FILE=$1
RESULT_DIR=result/$$
TDK_PATH=$COMMON_PATH/$PATH

cd $TDK_PATH

echo_start "mainTdk" $$

/bin/mkdir $TDK_PATH/$RESULT_DIR
./getTdk.php $IN_FILE $$

echo_end "mainTdk" $$
