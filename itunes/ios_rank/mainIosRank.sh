#!/bin/sh
source ../../conf/common.conf
source ../../conf/ios_rank.conf
source ../../lib/common.sh

IN_FILE=$1
WORK_DIR=work/$$
RESULT_DIR=result/$$
IOS_RANK_PATH=$COMMON_PATH/$PATH

cd $IOS_RANK_PATH
/bin/mkdir $IOS_RANK_PATH/$WORK_DIR
/bin/mkdir $IOS_RANK_PATH/$RESULT_DIR

echo_start "mainIosRank" $$

while read category type
do
    ./getIosRank.php "$category" $type $LIMIT $$
done < $IN_FILE
wait

echo_start "mainIosRank union" $$
/bin/cat $RESULT_DIR/* > $RESULT_DIR/union.tsv
echo_end "mainIosRank union" $$

echo_end "mainIosRank" $$
