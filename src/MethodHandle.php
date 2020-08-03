<?php


namespace Niexiawei\HyperfRabbitmqRpc;


class MethodHandle
{
    public static $mapping = [];

    public function handle($method,$param){

        if (!isset(self::$mapping[$method])){
            throw new \Exception('不存在的rpc方法');
        }
        $class = make(self::$mapping[$method][0]);
        return $class->{self::$mapping[$method][1]}(...$param);
    }
}
