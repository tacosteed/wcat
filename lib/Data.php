<?php
/**
 * @file
 * @brief WEBスクレイピングクラス
 * @author yano-tatsuya
 * @date 2014-05-20
 */

class Data
{

    private $_result = null;
    private $_error_no = null;
    private $_error_msg = null;

    public function setResult($result)
    {
        $this->_result = $result;
        return;
    }

    public function setErrorNo($no)
    {
        $this->_error_no = $no;
        return;
    }

    public function setErrorMsg($msg)
    {
        $this->_error_msg = $result;
        return;
    }

    public function getResult()
    {
        return $this->_result;
    }

    public function getErrorNo()
    {
        return $this->_error_no;
    }

    public function getErrorMsg()
    {
        return $this->_error_msg;
    }

}

