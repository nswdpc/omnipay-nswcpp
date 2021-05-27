<?php

namespace Omnipay\NSWGOVCPP;

use Firebase\JWT\JWT;
use Omnipay\NSWGOVCPP\CompletePurchaseRequestException;
use Omnipay\NSWGOVCPP\UnprocessableEntityException;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle payment completion requests from the CPP
 *
 * The CPP will send a JWT which can be used to determine if the request was valid
 * At this point, no other validations are made. Your application should verify the JWT and handle the payment data
 * @author James
 */
class CompletePurchaseRequest extends AbstractAgencyRequest
{
    use GetterSetterParameterTrait;
    use NeedsJWTDecodeTrait;

    protected $exitOnError = true;

    /**
     * Set whether to exit or not on JWT decode errors
     * The default is true but this can be turned off for testing purposes
     */
    public function setExitOnError(bool $exit)
    {
        $this->exitOnError = $exit;
        return $this;
    }

    /**
     * Return exitOnError value
     */
    public function getExitOnError() : bool
    {
        return $this->exitOnError;
    }

    /**
     * Validate, decode and return the JWT payload as the data for the CompletePurchaseResponse
     * @return array
     * @throws JWTDecodeException
     */
    public function getData() : array
    {
        $this->decodeJWT();
        return $this->jwtPayload;
    }

    /**
     * Create a CompletePurchaseResponse instance to represent payment completion
     * @param array $data
     * @throws JWTDecodeException
     */
    public function sendData($data) : ResponseInterface
    {

        // check that there is a payload
        if (empty($data)) {
            throw new JWTDecodeException("The decoded JWT payload was empty");
        }

        // create the complete purchase response, provide it the JWT payload
        $response = new CompletePurchaseResponse($this, $data);
        return $response;
    }
}
