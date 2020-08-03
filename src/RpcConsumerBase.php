<?php


namespace Niexiawei\HyperfRabbitmqRpc;

use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use Hyperf\Framework\Logger\StdoutLogger;
use PhpAmqpLib\Message\AMQPMessage;
use Hyperf\Di\Annotation\Inject;

class RpcConsumerBase extends ConsumerMessage
{
    /**
     * @Inject
     * @var MethodHandle
     */
    protected $handle;

    /**
     * @Inject
     * @var StdoutLogger
     */

    protected $logs;

    /**
     * @param $data
     * @param AMQPMessage $message
     * @return string
     */

    public function consumeMessage($data, AMQPMessage $message): string
    {
        try {
            $res_data = $this->handle->handle($data['method'], $data['param']);
            $response = new ReplyResponse(1, $res_data, 'ok', '');
        } catch (\Throwable $exception) {
            $this->logs->error('Rpc错误:'.$data['method']);
            $this->logs->error('Rpc错误：'.$exception->getMessage());
            $this->logs->error('Rpc错误：'.$exception->getTraceAsString());
            $response = new ReplyResponse($exception->getCode(), [], '', $exception->getMessage());
        }

        $this->reply(serialize($response), $message);
        return Result::ACK;
    }
}
