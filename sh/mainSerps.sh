#!/bin/sh
source ../include/common.conf
source ../include/serps.conf
source ../lib/common.sh

IN_FILE=$1
RESULT_DIR=result/$$
SERPS_PATH=$COMMON_PATH/$PATH

echo_start "mainSerps" $$

while read keyword
do
    $SERPS_PATH/getSerps.php "$keyword" $MAX_RANK $PAGE_CNT $$
done < $IN_FILE
wait

echo_start "mainSerps union" $$
/bin/cat $RESULT_DIR/* > $RESULT_DIR/union.tsv
echo_end "mainSerps union" $$

echo_end "mainSerps" $$
