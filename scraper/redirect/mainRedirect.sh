#!/bin/sh
source ../../conf/common.conf
source ../../conf/redirect.conf
source ../../lib/common.sh

IN_FILE=$1
RESULT_DIR=result/$$
REDIRECT_PATH=$COMMON_PATH/$PATH

cd $REDIRECT_PATH

echo_start "mainRedirect" $$

/bin/mkdir $REDIRECT_PATH/$RESULT_DIR
./checkRedirect.php $IN_FILE $$

echo_end "mainRedirect" $$
