<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Middleware\ExceptionMiddleware;
use App\Http\Middleware\RouteMiddleware;
use App\Http\RequestHandlers\FallbackRequestHandler;
use App\Http\RequestHandlers\QueueRequestHandler;
use App\Http\RequestHandlers\RequestHandlerFactory;
use Datashaman\Phial\RequestHandlerFactoryInterface;
use DI\FactoryInterface;
use FastRoute\Dispatcher;
use GuzzleHttp\Client;
use Interop\Container\ServiceProviderInterface;
use Invoker\InvokerInterface;
use Laminas\Diactoros\RequestFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class HttpServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            ClientInterface::class => fn(ContainerInterface $container) =>
                $container->get(Client::class),
            RequestFactoryInterface::class => fn(ContainerInterface $container) =>
                $container->get(RequestFactory::class),
            RequestHandlerFactoryInterface::class => fn(ContainerInterface $container) =>
                new RequestHandlerFactory(
                    $container->get('app.middleware'),
                    $container->get(FallbackRequestHandler::class),
                    $container->get(FactoryInterface::class)
                ),
            ServerRequestFactoryInterface::class => fn(ContainerInterface $container) =>
                $container->get(ServerRequestFactory::class),
            StreamFactoryInterface::class => fn(ContainerInterface $container) =>
                $container->get(StreamFactory::class),
        ];
    }

    public function getExtensions()
    {
        return [];
    }
}
