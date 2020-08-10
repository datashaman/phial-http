<?php

declare(strict_types=1);

namespace App\Providers;

use AsyncAws\Core\Configuration;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class AwsServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            Configuration::class => function (ContainerInterface $container) {
                return Configuration::create($container->get('aws.core.configuration'));
            },
        ];
    }

    public function getExtensions()
    {
        return [];
    }
}
