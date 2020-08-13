<?php


namespace Niexiawei\HyperfRabbitmqRpc\Exception\Handle;


use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Niexiawei\HyperfRabbitmqRpc\Exception\RpcServiceException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class RpcServiceExceptionHandle extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof RpcServiceException) {
            $responseData = [
                'code' => 0,
                'method' => $throwable->method ?? '',
                'error' => $throwable->getMessage()
            ];
            $this->stopPropagation();

            return $response->withHeader("Server", "Hyperf")
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody(new SwooleStream(Json::encode($responseData)));
        }
        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

}
