<?php


namespace Niexiawei\HyperfRabbitmqRpc;

use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use PhpAmqpLib\Message\AMQPMessage;

class RpcConsumerBase extends ConsumerMessage
{
    public function consumeMessage($data, AMQPMessage $message): string
    {
        $handle = make(MethodHandle::class);
        try {
            $res_data = $handle->handle($data['method'], $data['param']);
            $response = new ReplyResponse(1,$res_data,'ok',null);
        } catch (\Throwable $exception) {
            $response = new ReplyResponse($exception->getCode(),[],null,$exception->getMessage());
        }

        $this->reply(serialize($response), $message);
        return Result::ACK;
    }
}
