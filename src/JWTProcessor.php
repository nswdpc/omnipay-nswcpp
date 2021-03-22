<?php

namespace Omnipay\NSWGOVCPP;

use Firebase\JWT\JWT;

class JWTProcessor {

    public static function decode(string $token, string $key, array $algos, int $leeway = 0) {
        JWT::$leeway = $leeway;
        $decoded = JWT::decode($token, $key, $algos);
        return $decoded;
    }

}
