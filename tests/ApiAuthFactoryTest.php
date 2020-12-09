<?php

declare(strict_types=1);

namespace ApiAuth\Test;

use ApiAuth\ApiAuth;
use ApiAuth\ApiAuthFactory;
use ApiAuth\Authenticator\Authenticator;
use ApiAuth\Output\Output;
use ApiAuth\Strategy\Strategy;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \ApiAuth\ApiAuthFactory
 */
class ApiAuthFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $container->method('get')->will($this->returnValueMap([
            [Strategy::class, $this->createStub(Strategy::class)],
            [Authenticator::class, $this->createStub(Authenticator::class)],
            [Output::class, $this->createStub(Output::class)],
        ]));

        $apiAuth = (new ApiAuthFactory())($container);
        $this->assertInstanceOf(ApiAuth::class, $apiAuth);
    }
}
