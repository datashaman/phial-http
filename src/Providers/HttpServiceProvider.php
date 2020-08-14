<?php

declare(strict_types=1);

namespace Datashaman\Phial\Http\Providers;

use Datashaman\Phial\Http\Middleware\ExceptionMiddleware;
use Datashaman\Phial\Http\Middleware\RouteMiddleware;
use Datashaman\Phial\Http\Middleware\FallbackMiddleware;
use Datashaman\Phial\Http\Events\RequestEvent;
use Datashaman\Phial\Http\Factories\RequestHandlerFactory;
use Datashaman\Phial\Http\Factories\RequestHandlerFactoryInterface;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;

use function FastRoute\simpleDispatcher;

class HttpServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            Dispatcher::class => fn(ContainerInterface $container) =>
                simpleDispatcher(
                    function (RouteCollector $r) use ($container) {
                        foreach ($container->get('route.files') as $file) {
                            require $file;
                        }
                    }
                ),
            ExceptionMiddleware::class => fn(ContainerInterface $container)  =>
                new ExceptionMiddleware(
                    $container->get(LoggerInterface::class),
                    is_true($container->get('app.debug'))
                ),
            RequestHandlerFactoryInterface::class => fn(ContainerInterface $container) =>
                new RequestHandlerFactory(
                    $container->get('http.middleware'),
                    $container
                ),
        ];
    }

    public function getExtensions()
    {
        return [];
    }
}
