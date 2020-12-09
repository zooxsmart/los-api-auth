<?php

declare(strict_types=1);

namespace ApiAuth\Test\Strategy;

use ApiAuth\AuthData;
use ApiAuth\Strategy\XApiKeyHeader;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

use function assert;

/**
 * @covers \ApiAuth\Strategy\XApiKeyHeader
 */
class XApiKeyHeaderTest extends TestCase
{
    public function testMissingHeader(): void
    {
        $strategy = new XApiKeyHeader();
        $request  = new ServerRequest();
        $this->expectExceptionCode(401);
        ($strategy)($request);
    }

    public function testMissingHeaderHotRequired(): void
    {
        $strategy = new XApiKeyHeader(false);
        $request  = new ServerRequest();
        $this->assertNull(($strategy)($request));
    }

    public function testEmptyHeader(): void
    {
        $strategy = new XApiKeyHeader();
        $request  = (new ServerRequest())->withHeader('X-Api-Key', '');
        $this->expectExceptionMessage('Empty x-api-key header');
        ($strategy)($request);
    }

    public function testValidHeader(): void
    {
        $strategy = new XApiKeyHeader();
        $request  = (new ServerRequest())->withHeader('X-Api-Key', '123');
        $authData = ($strategy)($request);
        assert($authData instanceof AuthData);
        $this->assertEquals('123', $authData->identity());
    }
}
