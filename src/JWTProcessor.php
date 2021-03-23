<?php

namespace Omnipay\NSWGOVCPP;

use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Response;

class JWTProcessor
{

    /**
     * Decode the JWT and provide error handling to return the correct error code
     * An UnprocessableEntityException will result in a 422 error code being returned
     * A JWTDecodeException will result in a 50x error code being returned, depending on the error (503 only currently)
     * Note that decoding errors could be transient e.g bad configuration so we return a 50x error wherever possible
     * Special cases of the JWT `iss` issuer claim are used to trigger HTTP codes for testing purposes only
     * @throws UnprocessableEntityException
     * @throws JWTDecodeException
     */
    public static function decode(string $token, string $key, array $algos, int $leeway = 0)
    {
        try {
            JWT::$leeway = $leeway;
            $payload = JWT::decode($token, $key, $algos);
        } catch (\Exception $e) {
            /**
             * catch all {@link Firebase\JWT\JWT} exceptions here
             */

            throw new JWTDecodeException("JWT decode failure - " . $e->getMessage(), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        // handle issuer claim
        if (!empty($payload->iss)) {
            switch ($payload->iss) {
                case 'NSWDPC-FakeGateway-422':
                    throw new UnprocessableEntityException("Issuer triggered a 422");
                    break;
                case 'NSWDPC-FakeGateway-50x':
                    throw new JWTDecodeException("Issuer triggered a 50x", Response::HTTP_SERVICE_UNAVAILABLE);
                    break;
                default:
                    // noop
                    break;
            }
        }

        return $payload;
    }
}
