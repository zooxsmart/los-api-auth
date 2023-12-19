<?php

declare(strict_types=1);

namespace Los\ApiAuth;

use Los\ApiAuth\Authenticator\Authenticator;
use Los\ApiAuth\Output\Output;
use Los\ApiAuth\Strategy\Strategy;
use Psr\Container\ContainerInterface;

use function assert;
use function is_array;

class ApiAuthFactory
{
    public function __invoke(ContainerInterface $container): ApiAuth
    {
        $config = $container->get('config');
        assert(is_array($config));

        /** @psalm-suppress MixedAssignment, MixedArrayAccess */
        $ignorePaths = $config['api-auth']['ignorePaths'] ?? $config['api-auth']['ignorePaths'] ?? [];

        /** @psalm-suppress MixedArgument */
        return new ApiAuth(
            $container->get(Strategy::class),
            $container->get(Authenticator::class),
            $container->get(Output::class),
            $ignorePaths,
        );
    }
}
