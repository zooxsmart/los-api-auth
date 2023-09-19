<?php

declare(strict_types=1);

namespace Los\ApiAuth\Authenticator;

use Los\ApiAuth\AuthData;

interface Authenticator
{
    public function __invoke(AuthData $authData): string;
}
