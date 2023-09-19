<?php

declare(strict_types=1);

namespace ApiAuth\Test\Output;

use ApiAuth\Output\ProblemDetailsOutput;
use ApiAuth\Output\ProblemDetailsOutputFactory;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/** @covers \ApiAuth\Output\ProblemDetailsOutputFactory */
class ProblemDetailsOutputFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $container->method('get')->willReturn($this->createStub(ProblemDetailsResponseFactory::class));
        $authenticator = (new ProblemDetailsOutputFactory())($container);
        $this->assertInstanceOf(ProblemDetailsOutput::class, $authenticator);
    }
}
