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
    /**
     * @var string
     */
    public $rpc;

    /**
     * @var string
     */

    public $service;

    /**
     * @var string "after|befor"
     */
    
    public $order = '';
}
