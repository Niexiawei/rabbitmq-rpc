<?php


namespace Niexiawei\HyperfRabbitmqRpc;

use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use PhpAmqpLib\Message\AMQPMessage;

class RpcConsumerBase extends ConsumerMessage
{
    /**
     * @param $data
     * @param AMQPMessage $message
     * @return string
     */

    public function consumeMessage($data, AMQPMessage $message): string
    {
        $handle = make(MethodHandle::class);
        try {
            $res_data = $handle->handle($data['method'], $data['param']);
            $response = new ReplyResponse(1,$res_data,'ok','');
        } catch (\Throwable $exception) {
            $response = new ReplyResponse($exception->getCode(),[],'',$exception->getMessage());
        }

        $this->reply(serialize($response), $message);
        return Result::ACK;
    }
}
