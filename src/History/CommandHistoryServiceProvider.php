<?php

namespace Jakmall\Recruitment\Calculator\History;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Jakmall\Recruitment\Calculator\Container\ContainerServiceProviderInterface;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\History\CommandHistory;

class CommandHistoryServiceProvider implements ContainerServiceProviderInterface
{

    /**
     * @inheritDoc
     */
    public function register(Container $container, $config=NULL): void
    {
        
        $container->bind(
            CommandHistoryManagerInterface::class,
            function () use ($config) {
                //todo: register implementation
                return new CommandHistory($config);
            }
        );
    }
}
