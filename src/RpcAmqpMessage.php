<?php


namespace Niexiawei\HyperfRabbitmqRpc;


use Hyperf\Amqp\Message\RpcMessage;

class RpcAmqpMessage extends RpcMessage
{
    public function __construct(string $exchange, string $routingKey, $data,$pool_name = 'default')
    {
        $this->exchange = $exchange;
        $this->routingKey = $routingKey;
        $this->payload = $data;
        $this->poolName = $pool_name;
    }
}
