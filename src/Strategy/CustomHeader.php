<?php

declare(strict_types=1);

namespace ApiAuth\Strategy;

use ApiAuth\AuthData;
use ApiAuth\Exception\InvalidHeader;
use ApiAuth\Exception\MissingHeader;
use Psr\Http\Message\ServerRequestInterface;

use function sprintf;

class CustomHeader implements Strategy
{
    private bool $required;
    private string $header;

    public function __construct(string $header, bool $required = true)
    {
        $this->required = $required;
        $this->header   = $header;
    }

    public function __invoke(ServerRequestInterface $request): ?AuthData
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
