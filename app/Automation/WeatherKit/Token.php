<?php

namespace App\Automation\WeatherKit;

use Firebase\JWT\JWT;
use OpenSSLAsymmetricKey;
use Throwable;
use Exceptions\DecodingKeyFailedException;
use Exceptions\KeyNotFoundException;
use Exceptions\TokenFailedException;

/**
 * Token.
 *
 */

class Token
{
    protected string $token;

    public function __construct()
    {

        $pathToKey   = 'credentials/' . config('weatherkit.apple.authkeyfile');
        $keyId       = config('weatherkit.apple.keyid');
        $appIdPrefix = config('weatherkit.apple.teamid');
        $bundleId    = config('weatherkit.apple.serviceid');

        if (!file_exists(filename: $pathToKey)) {
            throw new KeyNotFoundException(message: 'Invalid path to key.', code: 400);
        }

        $key = $this->decodeKey(filename: $pathToKey);

        try {
            // Generate token.
            $this->token = JWT::encode(payload: [
                'iss' => $appIdPrefix,
                'sub' => $bundleId,
                'iat' => time(),
                'exp' => time() + 3600,
            ], head: [
                'id' => $appIdPrefix . '.' . $bundleId
            ], keyId: $keyId, key: $key, alg: 'ES256');
        } catch (Throwable $e) {
            throw new TokenFailedException(message: 'Could not generate token', code: 400, previous: $e);
        }
    }

    protected function decodeKey(string $filename): OpenSSLAsymmetricKey
    {
        $key = openssl_pkey_get_private(private_key: file_get_contents(filename: $filename));
        if (!$key) {
            throw new DecodingKeyFailedException(message: 'Could not decode key.', code: 400);
        }

        return $key;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function __toString(): string
    {
        return $this->getToken();
    }

}
