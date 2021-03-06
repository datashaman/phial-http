<?php

declare(strict_types=1);

namespace Datashaman\Phial\Http\Providers;

use Datashaman\Phial\ConfigInterface;
use Datashaman\Phial\Http\RequestHandlerFactory;
use Datashaman\Phial\Http\RequestHandlerFactoryInterface;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

use function FastRoute\simpleDispatcher;

class HttpServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            Dispatcher::class => function (ContainerInterface $container) {
                $config = $container->get(ConfigInterface::class);

                return simpleDispatcher(
                    function (RouteCollector $r) use ($config) {
                        foreach ($config->get('route.files') as $file) {
                            require $file;
                        }
                    }
                );
            },
            RequestHandlerFactoryInterface::class => fn(ContainerInterface $container) =>
                $container->get(RequestHandlerFactory::class),
        ];
    }

    public function getExtensions()
    {
        return [];
    }
}
