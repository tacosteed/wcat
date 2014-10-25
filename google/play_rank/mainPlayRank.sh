#!/bin/sh
source ../../conf/common.conf
source ../../conf/play_rank.conf
source ../../lib/common.sh

IN_FILE=$1
WORK_DIR=work/$$
RESULT_DIR=result/$$
PLAY_RANK_PATH=$COMMON_PATH/$PATH

cd $PLAY_RANK_PATH
/bin/mkdir $PLAY_RANK_PATH/$WORK_DIR
/bin/mkdir $PLAY_RANK_PATH/$RESULT_DIR

echo_start "mainPlayRank" $$

while read keyword
do
    ./getSerps.php "$keyword" $MAX_RANK $PAGE_CNT $$
done < $IN_FILE
wait

echo_start "mainPlayRank union" $$
/bin/cat $RESULT_DIR/* > $RESULT_DIR/union.tsv
echo_end "mainPlayRank union" $$

echo_end "mainPlayRank" $$
