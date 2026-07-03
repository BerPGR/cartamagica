<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService {
    private string $secret;
    private int $expiration;
    private string $algorithm;

    public function __construct() {
        $config = Flight::get('auth_config');
        $this->secret = $config['jwt_secret'];
        $this->expiration = $config['jwt_expiration'];
        $this->algorithm = $config['jwt_algorithm'];
    }

    public function generate(array $payload)
    {
        $now = time();

        $tokenData = array_merge($payload, [
            'iat' => $now,
            'exp' => $now + $this->expiration,
        ]);

        return JWT::encode($tokenData, $this->secret, $this->algorithm);
    }

    public function validate(string $token) {
        try {
            return JWT::decode($token, new Key($this->secret, $this->algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }
}