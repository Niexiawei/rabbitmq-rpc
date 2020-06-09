<?php


namespace Niexiawei\HyperfRabbitmqRpc\Amqp;

use Hyperf\Amqp\Consumer;
use Hyperf\Amqp\ConsumerManager as ManageBase;
use Hyperf\Amqp\Message\ConsumerMessageInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Process\AbstractProcess;
use Hyperf\Process\ProcessManager;
use Niexiawei\HyperfRabbitmqRpc\Annotation\RpcConsumer;
use Niexiawei\HyperfRabbitmqRpc\Exception\RpcServiceUndefinedException;
use Psr\Container\ContainerInterface;

class ConsumerManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function run()
    {
        $classes = AnnotationCollector::getClassByAnnotation(RpcConsumer::class);
        /**
         * @var string
         * @var RpcConsumer $annotation
         */

        $config = $this->container->get(ConfigInterface::class);

        foreach ($classes as $class => $annotation) {
            $instance = make($class);
            
            if (!$instance instanceof ConsumerMessageInterface) {
                continue;
            }
            
            $rpc_config = $config->get('rabbitmq_rpc.' . $annotation->rpc);
            if (empty($rpc_config)) {
                throw new RpcServiceUndefinedException('rpc服务未定义');
            }

            $instance->setExchange($rpc_config['exchange']);
            $instance->setRoutingKey($rpc_config['routingKey']);
            $annotation->queue && $instance->setQueue($rpc_config['routingKey'].'.'.$annotation->queue);
            !is_null($annotation->enable) && $instance->setEnable($annotation->enable);
            property_exists($instance, 'container') && $instance->container = $this->container;
            $annotation->maxConsumption && $instance->setMaxConsumption($annotation->maxConsumption);
            $nums = $annotation->nums;
            $process = $this->createProcess($instance);
            $process->nums = (int)$nums;
            $process->name = $annotation->name . '-' . $instance->getQueue();
            ProcessManager::register($process);
        }
    }

    private function createProcess(ConsumerMessageInterface $consumerMessage): AbstractProcess
    {
        return new class($this->container, $consumerMessage) extends AbstractProcess {
            /**
             * @var \Hyperf\Amqp\Consumer
             */
            private $consumer;

            /**
             * @var ConsumerMessageInterface
             */
            private $consumerMessage;

            public function __construct(ContainerInterface $container, ConsumerMessageInterface $consumerMessage)
            {
                parent::__construct($container);
                $this->consumer = $container->get(Consumer::class);
                $this->consumerMessage = $consumerMessage;
            }

            public function handle(): void
            {
                $this->consumer->consume($this->consumerMessage);
            }

            public function getConsumerMessage(): ConsumerMessageInterface
            {
                return $this->consumerMessage;
            }

            public function isEnable(): bool
            {
                return $this->consumerMessage->isEnable();
            }
        };
    }
}
