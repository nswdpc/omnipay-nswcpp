<?php

namespace OmniPay\NSWGOVCPP;

use Firebase\JWT\JWT;
use OmniPay\NSWGOVCPP\CompletePurchaseRequestException;
use OmniPay\NSWGOVCPP\UnprocessableEntityException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Handle payment completion requests from the CPP
 *
 * The CPP will send a JWT which can be used to determine if the request was valid
 * At this point, no other validations are made. Your application should verify the JWT and handle the payment data
 * @author James
 */
class CompletePurchaseRequest extends AbstractRequest
{

    /**
     * Whether the purchase reqest params were validate successfully
     * @var boolean
     */
    protected $validated = false;

    /**
     * Store the JWT payload
     * @var array
     */
    protected $jwtPayload = [];

    /**
     * Initialise the purchase request
     */
    public function initialize(array $parameters = array())
    {

        $this->validateJWT($parameters);

        $this->validated = true;

        return parent::initialize($parameters);
    }

    /**
     * Validate the JWT
     * @throws CompletePurchaseRequestException|UnprocessableEntityException
     */
    public function validateJWT(array &$parameters) : boolean {
        if(empty($parameters['token'])) {
            throw new CompletePurchaseRequestException("The JWT is not present or empty");
        }
        if(empty($parameters['jwtSecret'])) {
            throw new CompletePurchaseRequestException("The JWT secret key is not present or empty");
        }

        try {
            // TODO configure leeway
            JWT::$leeway = 60;
            $decoded = JWT::decode($parameters['token'], $parameters['jwtSecret'], ['RS256','HS256']);
            // set parameters and return
            $this->jwtPayload = (array) $decoded;
            return true;
        } catch (\Exception $e) {
            // catch JWT exceptions
        }

        throw new UnprocessableEntityException("The JWT could not be verified");
    }

    /**
     * Returns the JWT payload
     * @return array
     */
     public function getData() : array {
        return $this->jwtPayload;
     }

     /**
      * Create a CompletePurchaseResponse instance to represent payment completion
      * @param array $data
      * @throws CompletePurchaseRequestException
      */
    public function sendData($data) : ResponseInterface {

        if(!$this->validated) {
            throw new CompletePurchaseRequestException("You cannot call sendData() without validating the parameters.");
        }

        // create the complete purchase response, provide it the JWT payload
        $response = new CompletePurchaseResponse($this, $data);

        // we must return the response on failure
        return $response;
    }
}
