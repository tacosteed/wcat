#!/bin/sh
source ../../conf/common.conf
source ../../conf/suggest.conf
source ../../lib/common.sh

IN_FILE=$1
RESULT_DIR=result/$$
SUGGEST_PATH=$COMMON_PATH/$PATH

cd $SUGGEST_PATH
/bin/mkdir $SUGGEST_PATH/$RESULT_DIR

echo_start "mainSuggest" $$

while read keyword
do
    ./getSuggest.php "$keyword" $MAX_COUNT $$
done < $IN_FILE
wait

echo_start "mainSuggest union" $$
/bin/cat $RESULT_DIR/* > $RESULT_DIR/union.tsv
echo_end "mainSuggest union" $$

echo_end "mainSuggest" $$
