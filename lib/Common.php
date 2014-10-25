<?php
/**
 * @file
 * @brief 共通処理クラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */

class Common
{

    /**
     * ファイル読み込み
     */
    public function readInputFile($file, $separate = "\t") {

        $ret = array();

        //インプットファイルOPEN
        if (!(is_readable($file) && ($fp = fopen($file, "rb")))) {
                die("Cannot Read InputFile");
        }

        while (($row = fgets($fp)) !== false) {

            $column = split($separate, str_replace(array("\r\n","\r","\n"), '', $row));
            $ret[count($ret)] = $column;

        }

        fclose ($fp);
        return $ret;
    }

}

