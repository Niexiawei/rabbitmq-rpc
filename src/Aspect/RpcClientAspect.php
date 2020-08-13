<?php


namespace Niexiawei\HyperfRabbitmqRpc\Aspect;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;
use Niexiawei\HyperfRabbitmqRpc\Annotation\RpcClient as Rpc;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Amqp\RpcClient as RabbitRpcClient;
use Niexiawei\HyperfRabbitmqRpc\Exception\RpcServiceException;
use Niexiawei\HyperfRabbitmqRpc\Exception\RpcServiceUndefinedException;
use Hyperf\Amqp\Message\DynamicRpcMessage;
use Niexiawei\HyperfRabbitmqRpc\ReplyResponse;
use Hyperf\Di\Annotation\Aspect;
use Niexiawei\HyperfRabbitmqRpc\RpcAmqpMessage;

/**
 * Class RpcAspect
 * @package App\Aspect
 * @Aspect()
 */
class RpcClientAspect extends AbstractAspect
{
    public $annotations = [
        Rpc::class
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {

        $rpc_service = '';
        $service_name = '';
        $methodMetaData = $proceedingJoinPoint->getAnnotationMetadata()->method[Rpc::class] ?? null;

        if ($methodMetaData instanceof Rpc) {
            $rpc_service = $methodMetaData->rpc;
            $service_name = $methodMetaData->service;
        }

        if (empty($rpc_service) || empty($service_name)) {
            throw new RpcServiceUndefinedException('rpc服务未定义');
        }

        $config = ApplicationContext::getContainer()->get(ConfigInterface::class);

        $service_config = $config->get('rabbitmq_rpc.' . $rpc_service);

        if (empty($service_config)) {
            throw new RpcServiceUndefinedException('rpc服务未定义');
        }

        if (!isset($service_config['exchange']) || !isset($service_config['routingKey'])) {
            throw new RpcServiceUndefinedException('rpc服务未定义');
        }

        $rpcClient = ApplicationContext::getContainer()->get(RabbitRpcClient::class);
        $res = $rpcClient->call(new RpcAmqpMessage($service_config['exchange'], $service_config['routingKey'], [
            'method' => $service_name,
            'param' => $proceedingJoinPoint->getArguments()
        ], $service_config['pool_name'] ?? 'default'));

        $response = unserialize($res);

        if (!$response instanceof ReplyResponse) {
            throw new RpcServiceException('未知的返回类型', '');
        }

        if ($response->code !== 1) {
            throw new RpcServiceException($response->error, $response->method);
        }

        return $response->data;
    }
}
