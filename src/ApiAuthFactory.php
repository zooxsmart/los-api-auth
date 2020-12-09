<?php

declare(strict_types=1);

namespace ApiAuth;

use ApiAuth\Authenticator\Authenticator;
use ApiAuth\Output\Output;
use ApiAuth\Strategy\Strategy;
use Psr\Container\ContainerInterface;

class ApiAuthFactory
{
    public function __invoke(ContainerInterface $container): ApiAuth
    {
        /** @psalm-suppress MixedArgument */
        return new ApiAuth(
            $container->get(Strategy::class),
            $container->get(Authenticator::class),
            $container->get(Output::class),
        );
    }
}
