<?php


namespace Niexiawei\HyperfRabbitmqRpc;


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
            throw new \Exception(sprintf("不存在的rpc方法:%s",$method));
        }
        $class = make($this->mapping[$method][0]);
        return $class->{$this->mapping[$method][1]}(...$param);
    }
}
