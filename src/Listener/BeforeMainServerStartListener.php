<?php


namespace Niexiawei\HyperfRabbitmqRpc\Listener;


use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BeforeMainServerStart;
use Psr\Container\ContainerInterface;
use Niexiawei\HyperfRabbitmqRpc\Amqp\ConsumerManager;

class BeforeMainServerStartListener implements ListenerInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            BeforeMainServerStart::class,
        ];
    }


    public function process(object $event)
    {
        // Init the consumer process.
        $consumerManager = $this->container->get(ConsumerManager::class);
        $consumerManager->run();
    }

}
