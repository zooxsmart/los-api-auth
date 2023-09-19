<?php

declare(strict_types=1);

namespace Los\ApiAuth\Strategy;

use Los\ApiAuth\AuthData;
use Los\ApiAuth\Exception\InvalidHeader;
use Los\ApiAuth\Exception\MissingHeader;
use Psr\Http\Message\ServerRequestInterface;

use function sprintf;

class CustomHeader implements Strategy
{
    public function __construct(private string $header, private bool $required = true)
    {
    }

    public function __invoke(ServerRequestInterface $request): AuthData|null
    {
        if (! $request->hasHeader($this->header)) {
            if ($this->required) {
                throw new MissingHeader(sprintf('Missing %s header', $this->header), 401);
            }

            return null;
        }

        $token = $request->getHeader($this->header)[0] ?? '';

        if (empty($token)) {
            throw new InvalidHeader(sprintf('Empty %s header', $this->header), 401);
        }

        return new AuthData($token);
    }
}
