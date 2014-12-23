#!/bin/sh
source ../include/common.conf
source ../include/morphological.conf
source ../lib/common.sh

IN_FILE=$1
RESULT_DIR=/var/local/data/wcat/$PATH/result/$$
MORPHOLOGICAL_PATH=$COMMON_PATH/$PATH

echo_start "mainMorphologicalAnalysis" $$

$MORPHOLOGICAL_PATH/ToTheMorphologicalAnalysis.php $IN_FILE $$ $2 $3

echo_end "mainMorphologicalAnalysis" $$
