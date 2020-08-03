<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Niexiawei\HyperfRabbitmqRpc;

use Niexiawei\HyperfRabbitmqRpc\Listener\BeforeMainServerStartListener;
use Niexiawei\HyperfRabbitmqRpc\Listener\MainWorkerStartListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'listeners' => [
                BeforeMainServerStartListener::class,
                MainWorkerStartListener::class
            ],
            'annotations' => [
                'scan' => [
                    'collectors'=>[

                    ],
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for rabbitmq_rpc.',
                    'source' => __DIR__ . '/../publish/rabbitmq_rpc.php',
                    'destination' => BASE_PATH . '/config/autoload/rabbitmq_rpc.php',
                ],
            ],
        ];
    }
}
