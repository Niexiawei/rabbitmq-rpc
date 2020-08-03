<?php


namespace Niexiawei\HyperfRabbitmqRpc\Listener;


use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BeforeMainServerStart;
use Hyperf\Framework\Event\MainWorkerStart;
use Niexiawei\HyperfRabbitmqRpc\Annotation\RpcServer;
use Niexiawei\HyperfRabbitmqRpc\MethodHandle;
use Hyperf\Di\Annotation\Inject;

class RpcServerAnnotationListener implements ListenerInterface
{

    /**
     * @Inject
     * @var MethodHandle
     */

    protected $handle;

    public function listen(): array
    {
        return [
            BeforeMainServerStart::class
        ];
    }

    public function process(object $event)
    {
        $methods = AnnotationCollector::getMethodsByAnnotation(RpcServer::class);
        foreach ($methods as $method) {
            if ($method['annotation'] instanceof RpcServer) {
                $this->handle->setMapping($method['annotation']->service,[$method['class'], $method['method']]);
            }
        }
    }
}


