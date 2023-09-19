<?php

declare(strict_types=1);

namespace Los\ApiAuth\Strategy;

use Los\ApiAuth\AuthData;
use Psr\Http\Message\ServerRequestInterface;

interface Strategy
{
    public function __invoke(ServerRequestInterface $request): AuthData|null;
}
