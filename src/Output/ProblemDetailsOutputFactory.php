<?php

declare(strict_types=1);

namespace ApiAuth\Output;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

class ProblemDetailsOutputFactory
{
    public function __invoke(ContainerInterface $container): ProblemDetailsOutput
    {
        /** @psalm-suppress MixedArgument */
        return new ProblemDetailsOutput($container->get(ProblemDetailsResponseFactory::class));
    }
}
