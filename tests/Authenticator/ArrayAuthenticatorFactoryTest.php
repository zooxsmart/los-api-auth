<?php

declare(strict_types=1);

namespace ApiAuth\Test\Authenticator;

use ApiAuth\Authenticator\ArrayAuthenticator;
use ApiAuth\Authenticator\ArrayAuthenticatorFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/** @covers \ApiAuth\Authenticator\ArrayAuthenticatorFactory */
class ArrayAuthenticatorFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $container->method('get')->willReturn([]);

        $authenticator = (new ArrayAuthenticatorFactory())($container);
        $this->assertInstanceOf(ArrayAuthenticator::class, $authenticator);
    }
}
