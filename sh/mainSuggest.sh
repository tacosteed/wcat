#!/bin/sh
source ../include/common.conf
source ../include/suggest.conf
source ../lib/common.sh

IN_FILE=$1
RESULT_DIR=/var/local/data/wcat/google/suggest/result/$$
SUGGEST_PATH=$COMMON_PATH/$PATH

echo_start "mainSuggest" $$

while read keyword
do
    $SUGGEST_PATH/getSuggest.php "$keyword" $MAX_COUNT $$
done < $IN_FILE
wait

echo_start "mainSuggest union" $$
/bin/cat $RESULT_DIR/* > $RESULT_DIR/union.tsv
echo_end "mainSuggest union" $$

echo_end "mainSuggest" $$
