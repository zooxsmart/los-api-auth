<?php

declare(strict_types=1);

namespace Los\ApiAuth\Strategy;

use Los\ApiAuth\AuthData;
use Los\ApiAuth\Exception\InvalidHeader;
use Los\ApiAuth\Exception\MissingHeader;
use Psr\Http\Message\ServerRequestInterface;

use function base64_decode;
use function count;
use function explode;
use function preg_match;
use function substr;

final class BasicAuthorizationHeader implements Strategy
{
    public function __construct(private bool $required = true)
    {
    }

    public function __invoke(ServerRequestInterface $request): AuthData|null
    {
        if (! $request->hasHeader('authorization')) {
            if ($this->required) {
                throw new MissingHeader('Missing Authorization header', 401);
            }

            return null;
        }

        $token = $request->getHeader('authorization');

        $token = $token[0] ?? '';

        if (! preg_match('/^basic/i', $token)) {
            throw new InvalidHeader('Invalid Authorization header', 401);
        }

        $auth   = base64_decode(substr($token, 6));
        $tokens = explode(':', $auth);
        if (count($tokens) !== 2) {
            throw new InvalidHeader('Invalid Authorization header', 401);
        }

        return new AuthData($tokens[0], $tokens[1]);
    }
}
