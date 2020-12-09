<?php

declare(strict_types=1);

namespace ApiAuth\Authenticator;

use ApiAuth\AuthData;

interface Authenticator
{
    public function __invoke(AuthData $authData): string;
}
