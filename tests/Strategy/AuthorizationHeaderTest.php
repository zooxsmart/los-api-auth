<?php

declare(strict_types=1);

namespace ApiAuth\Test\Strategy;

use ApiAuth\AuthData;
use ApiAuth\Strategy\AuthorizationHeader;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

use function assert;
use function base64_encode;
use function sprintf;

/**
 * @covers \ApiAuth\Strategy\AuthorizationHeader
 */
class AuthorizationHeaderTest extends TestCase
{
    public function testMissingHeader(): void
    {
        $strategy = new AuthorizationHeader();
        $request  = new ServerRequest();
        $this->expectExceptionCode(401);
        ($strategy)($request);
    }

    public function testMissingHeaderHotRequired(): void
    {
        $strategy = new AuthorizationHeader(false);
        $request  = new ServerRequest();
        $this->assertNull(($strategy)($request));
    }

    public function testEmptyHeader(): void
    {
        $strategy = new AuthorizationHeader();
        $request  = (new ServerRequest())->withHeader('Authorization', '');
        $this->expectExceptionCode(401);
        ($strategy)($request);
    }

    public function testInvalidHeader(): void
    {
        $strategy = new AuthorizationHeader();
        $request  = (new ServerRequest())->withHeader('Authorization', 'Basic: 123');
        $this->expectExceptionCode(401);
        ($strategy)($request);
    }

    public function testValidHeader(): void
    {
        $identity   = 'abc';
        $credential = '123';

        $strategy = new AuthorizationHeader();
        $request  = (new ServerRequest())->withHeader(
            'Authorization',
            'Basic: ' . base64_encode(sprintf('%s:%s', $identity, $credential))
        );
        $authData = ($strategy)($request);
        assert($authData instanceof AuthData);
        $this->assertEquals($identity, $authData->identity());
        $this->assertEquals($credential, $authData->credential());
    }
}
