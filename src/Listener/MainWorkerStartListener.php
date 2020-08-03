<?php


namespace Niexiawei\HyperfRabbitmqRpc\Listener;


use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\MainWorkerStart;
use Niexiawei\HyperfRabbitmqRpc\Annotation\RpcServer;
use Niexiawei\HyperfRabbitmqRpc\MethodHandle;

class MainWorkerStartListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            MainWorkerStart::class
        ];
    }

    public function process(object $event)
    {
        $methods = AnnotationCollector::getMethodsByAnnotation(RpcServer::class);
        foreach ($methods as $method) {
            if ($method['annotation'] instanceof RpcServer) {
                //$annotation->service
                MethodHandle::$mapping[$method['annotation']->service] = [$method['class'], $method['method']];
            }
        }
    }
}


