<?php

declare(strict_types=1);

namespace ApiAuth\Output;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerInterface;

use function assert;

class ProblemDetailsOutputFactory
{
    public function __invoke(ContainerInterface $container): ProblemDetailsOutput
    {
        $factory = $container->get(ProblemDetailsResponseFactory::class);

        assert($factory instanceof ProblemDetailsResponseFactory);

        return new ProblemDetailsOutput($factory);
    }
}
