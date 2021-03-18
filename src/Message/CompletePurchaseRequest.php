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

    /**
     * Store the JWT payload
     * @var array
     */
    private $jwtPayload = [];

    /**
     * Whether the JWT was validated
     * @var boolean
     */
    private $jwtValidated = false;

    /**
     * JWT Leeway (seconds)
     * @var int
     */
    public $jwtLeeway = 60;

    /**
     * JWT Algos available for decoding token
     * @var array
     */
    public $jwtAlgos = ['RS256'];

    /**
     * Set the JWT sent from the CPP system
     * @param string $jwt
     */
    public function setJwt($jwt) {
        $this->setParameter('jwt', $jwt);
    }

    /**
     * Get the JWT sent from the CPP system
     * @return string $jwt
     */
    public function getJwt() : string {
        return $this->getParameter('jwt');
    }

    /**
     * Validate the JWT
     * @param boolean $force
     * @throws CompletePurchaseRequestException|UnprocessableEntityException
     */
    public function validateJWT($force = false) : bool {

        // if already validated
        if($this->jwtValidated && !$force) {
            return $this->jwtPayload;
        }

        if(!$token = $this->getJwt()) {
            throw new CompletePurchaseRequestException("The JWT is not present or empty");
        }
        if(!($jwtPublicKey = $this->getJwtPublicKey())) {
            throw new CompletePurchaseRequestException("The JWT public key is not present or empty");
        }

        try {
            $error = "";
            if($this->jwtLeeway > 0) {
                JWT::$leeway = $this->jwtLeeway;
            }
            $decoded = JWT::decode($token, $jwtPublicKey, $this->jwtAlgos);
            $this->jwtPayload = (array) $decoded;
            $this->jwtValidated = true;
            return true;
        } catch (\Exception $e) {
            // noop
            $error = $e->getMessage();
        }
        throw new UnprocessableEntityException("The JWT {$token} could not be verified: {$error}");
    }

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
