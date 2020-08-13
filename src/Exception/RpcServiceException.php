<?php


namespace Niexiawei\HyperfRabbitmqRpc\Exception;


use Throwable;

class RpcServiceException extends \Exception
{

    public $method;

    public function __construct($message,$method,$code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->method = $method;
    }
}
