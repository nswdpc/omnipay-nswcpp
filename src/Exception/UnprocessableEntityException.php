<?php

namespace Omnipay\NSWGOVCPP;

use Symfony\Component\HttpFoundation\Response;

/**
 * Specific exception that can be thrown by an application and caught in
 * CompletePurchaseResponse::complete(), resulting in the required 422 error code being returned to the gateway
 * @author James
 */
class UnprocessableEntityException extends \Exception
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;
}
