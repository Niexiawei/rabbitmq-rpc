<?php


namespace Niexiawei\HyperfRabbitmqRpc;


class ReplyResponse
{
    public $code;
    public $data;
    public $msg;
    public $error;

    public function __construct(int $code,$data,string $msg,string $error)
    {
        $this->data = $data;
        $this->code = $code;
        $this->msg = $msg;
        $this->error = $error;
    }
}
