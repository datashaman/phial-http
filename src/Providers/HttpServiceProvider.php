<?php

declare(strict_types=1);

namespace Datashaman\Phial\Http\Providers;

use App\Http\Middleware\ExceptionMiddleware;
use App\Http\Middleware\RouteMiddleware;
use App\Http\Middleware\FallbackMiddleware;
use App\Http\RequestHandlers\RequestHandlerFactory;
use App\Listeners\SetRequestAndContext;
use Datashaman\Phial\Http\Events\RequestEvent;
use Datashaman\Phial\Http\RequestHandlerFactoryInterface;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;

class HttpServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            Dispatcher::class => fn(ContainerInterface $container) =>
                simpleDispatcher(
                    function (RouteCollector $r) {
                        require base_dir('routes/web.php');
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
        return [
            ListenerProviderInterface::class => function (ContainerInterface $container, $provider) {
                $provider->addService(RequestEvent::class, SetRequestAndContext::class);

                return $provider;
            },
        ];
    }
}
