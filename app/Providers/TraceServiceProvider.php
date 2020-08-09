<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\TraceBegin;
use App\Listeners\TraceEnd;
use Datashaman\Phial\Events\RequestEvent;
use Datashaman\Phial\Events\ResponseEvent;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class TraceServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [];
    }

    public function getExtensions()
    {
        return [
            ListenerProviderInterface::class => function (ContainerInterface $container, $provider) {
                $provider->addService(RequestEvent::class, TraceBegin::class);
                $provider->addService(ResponseEvent::class, TraceEnd::class);

                return $provider;
            },
        ];
    }
}
