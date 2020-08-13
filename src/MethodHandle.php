<?php


namespace Niexiawei\HyperfRabbitmqRpc;


use Niexiawei\HyperfRabbitmqRpc\Exception\RpcServiceException;

class MethodHandle
{
    public $mapping = [];


    public function setMapping(string $service, array $handle)
    {
        $this->mapping[$service] = $handle;
    }

    public function handle($method, $param)
    {
        if (!isset($this->mapping[$method])) {
            throw new RpcServiceException(sprintf("不存在的rpc方法:%s",$method),'');
        }
        $class = make($this->mapping[$method][0]);
        return $class->{$this->mapping[$method][1]}(...$param);
    }
}
