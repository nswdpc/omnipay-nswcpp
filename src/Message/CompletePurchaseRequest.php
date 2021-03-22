<?php

namespace Omnipay\NSWGOVCPP;

use Firebase\JWT\JWT;
use Omnipay\NSWGOVCPP\CompletePurchaseRequestException;
use Omnipay\NSWGOVCPP\UnprocessableEntityException;
use Omnipay\Common\Message\ResponseInterface;

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

    /**
     * Validate and returns the JWT payload
     * @return array
     */
     public function getData() : array {
        $this->validateJWT();
        return $this->jwtPayload;
     }

     /**
      * Create a CompletePurchaseResponse instance to represent payment completion
      * @param array $data
      * @throws CompletePurchaseRequestException
      */
    public function sendData($data) : ResponseInterface {

        // check that there is a payload
        if(empty($data)) {
            throw new CompletePurchaseRequestException("The decoded JWT payload was empty");
        }

        // create the complete purchase response, provide it the JWT payload
        $response = new CompletePurchaseResponse($this, $data);
        return $response;
    }
}
