#!/bin/sh
source ../../conf/common.conf
source ../../conf/serps.conf
source ../../lib/common.sh

IN_FILE=$1
WORK_DIR=work/$$
RESULT_DIR=result/$$
SERPS_PATH=$COMMON_PATH/$PATH

cd $SERPS_PATH
/bin/mkdir $SERPS_PATH/$WORK_DIR
/bin/mkdir $SERPS_PATH/$RESULT_DIR

echo_start "mainSerps" $$

while read keyword
do
    ./getSerps.php "$keyword" $MAX_RANK $PAGE_CNT $$
done < $IN_FILE
wait

echo_start "mainSerps union" $$
/bin/cat $RESULT_DIR/* > $RESULT_DIR/union.tsv
echo_end "mainSerps union" $$

echo_end "mainSerps" $$
