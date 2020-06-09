<?php


namespace Niexiawei\HyperfRabbitmqRpc\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * Class Rpc
 * @package App\Annotation
 * @Annotation
 * @Target({"METHOD"})
 */
class RpcClient extends AbstractAnnotation
{
    public $rpc;

    public $service;
}
