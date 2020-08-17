<?php

declare(strict_types=1);

namespace Datashaman\Phial\Http;

use Datashaman\Phial\ConfigInterface;
use Northwoods\Broker\Broker;
use Northwoods\Middleware\LazyMiddlewareFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerFactory implements RequestHandlerFactoryInterface
{
    /**
     * @var array<string>
     * @psalm-var list<string>
     */
    private array $middleware;

    private ContainerInterface $container;

    public function __construct(
        ConfigInterface $config,
        ContainerInterface $container
    ) {
        $this->middleware = $config->get('http.middleware');
        $this->container = $container;
    }

    public function createRequestHandler(): RequestHandlerInterface
    {
        $broker = new Broker();
        $factory = new LazyMiddlewareFactory($this->container);

        foreach ($this->middleware as $middleware) {
            $broker->append($factory->defer($middleware));
        }

        return $broker;
    }
}
