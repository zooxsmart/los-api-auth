<?php

declare(strict_types=1);

namespace ApiAuth\Authenticator;

use ApiAuth\AuthData;
use ApiAuth\Exception\AuthorizationFailed;

use function array_key_exists;
use function array_search;
use function assert;
use function password_verify;

final class ArrayAuthenticator implements Authenticator
{
    /** @var string[]|array<string, string> */
    private array $identities;

    /**
     * @param string[]|array<string, string> $identities
     */
    public function __construct(array $identities)
    {
        $this->identities = $identities;
    }

    public function __invoke(AuthData $authData): string
    {
        if ($authData->credential() === null) {
            return $this->authorizeValue($authData);
        }

        return $this->authorizeKeyValue($authData);
    }

    private function authorizeKeyValue(AuthData $authData): string
    {
        $identity   = $authData->identity();
        $credential = $authData->credential();

        assert($credential !== null);

        if (! array_key_exists($identity, $this->identities)) {
            throw new AuthorizationFailed('Identity not found', 401);
        }

        if (
            $this->identities[$identity] !== $credential &&
            ! password_verify($credential, $this->identities[$identity])
        ) {
            throw new AuthorizationFailed('Credentials do not match', 401);
        }

        return $identity;
    }

    private function authorizeValue(AuthData $authData): string
    {
        if (array_search($authData->identity(), $this->identities) === false) {
            throw new AuthorizationFailed('Identity not found', 401);
        }

        return $authData->identity();
    }
}
