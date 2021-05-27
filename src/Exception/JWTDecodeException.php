<?php

namespace Omnipay\NSWGOVCPP;

use Symfony\Component\HttpFoundation\Response;

/**
 * Specific exception thrown when the incoming JWT cannot be decoded
 * This results in a 422 response as it is highly unlikely that further attempts in the CPP timeframe will succeed
 * @author James
 */
class JWTDecodeException extends UnprocessableEntityException
{
    public function getCode() {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}
