<?php


namespace Niexiawei\HyperfRabbitmqRpc\Annotation;


use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * Class RpcService
 * @package Niexiawei\HyperfRabbitmqRpc\Annotation
 * @Annotation
 * @Target({"CLASS"})
 */

class RpcConsumer extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $rpc;

    /**
     * @var string
     */
    public $queue = 'rpc.reply';

    /**
     * @var string
     */
    public $name = 'Consumer';

    /**
     * @var int
     */
    public $nums = 1;

    /**
     * @var null|bool
     */
    public $enable;

    /**
     * @var int
     */
    public $maxConsumption = 0;
}
