<?php

declare(strict_types=1);

namespace Los\ApiAuth\Test\Authenticator;

use Los\ApiAuth\Authenticator\ArrayAuthenticator;
use Los\ApiAuth\Authenticator\ArrayAuthenticatorFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/** @covers \Los\ApiAuth\Authenticator\ArrayAuthenticatorFactory */
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
