<?php


namespace Niexiawei\HyperfRabbitmqRpc;


class ReplyResponse
{
    public $code;
    public $data;
    public $msg;
    public $error;
    public $method;

    public function __construct($code,$data,string $msg,string $error,$method)
    {
        $this->data = $data;
        $this->code = $code;
        $this->msg = $msg;
        $this->error = $error;
        $this->method = $method;
    }
}
