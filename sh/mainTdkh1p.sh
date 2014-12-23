#!/bin/sh
source ../include/common.conf
source ../include/tdkh1p.conf
source ../lib/common.sh

IN_FILE=$1
TDKH1P_PATH=$COMMON_PATH/$PATH

echo_start "mainTdk" $$
$TDKH1P_PATH/getTdkh1p.php $IN_FILE $$ $2 $3
echo_end "mainTdk" $$
