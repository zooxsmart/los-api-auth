<?php

declare(strict_types=1);

namespace Los\ApiAuth\Test;

use Los\ApiAuth\AuthData;
use PHPUnit\Framework\TestCase;

/** @covers \ApiAuth\AuthData */
class AuthDataTest extends TestCase
{
    public function testIdentity(): void
    {
        $authData = new AuthData('key1');
        $this->assertEquals('key1', $authData->identity());
    }

    public function testCredential(): void
    {
        $authData = new AuthData('key1', 'pass1');
        $this->assertEquals('key1', $authData->identity());
        $this->assertEquals('pass1', $authData->credential());
    }

    public function testNullCredential(): void
    {
        $authData = new AuthData('key1');
        $this->assertNull($authData->credential());
    }

    public function testConstruct(): void
    {
        $authData = new AuthData('user1', 'pass1');
        $this->assertInstanceOf(AuthData::class, $authData);
    }

    public function testConstructAcceptsOnlyCredential(): void
    {
        $authData = new AuthData('key1');
        $this->assertInstanceOf(AuthData::class, $authData);
    }
}
