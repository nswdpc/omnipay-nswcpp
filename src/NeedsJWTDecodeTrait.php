<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Trait used by requests that need to do a JWT decode
 * In the case of NSWGOVCPP, this is payment completion
 * @author James
 */
trait NeedsJWTDecodeTrait
{

    /**
     * Store the JWT payload
     * @var array
     */
    protected $jwtPayload = [];

    /**
     * Whether the JWT was validated
     * @var boolean
     */
    protected $jwtValidated = false;

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
    public function setJwt($jwt)
    {
        $this->setParameter('jwt', $jwt);
    }

    /**
     * Get the JWT sent from the CPP system
     * @return string $jwt
     */
    public function getJwt() : string
    {
        return $this->getParameter('jwt');
    }

    /**
     * Validate the JWT
     * @param boolean $force
     * @return array
     * @throws JWTDecodeException
     */
    public function validateJWT($force = false) : array
    {
        // if already validated
        if ($this->jwtValidated && !$force) {
            return $this->jwtPayload;
        }
        if (!$token = $this->getJwt()) {
            throw new JWTDecodeException("The JWT is not present or empty");
        }
        if (!($jwtPublicKey = $this->getJwtPublicKey())) {
            throw new JWTDecodeException("The JWT public key is not present in configuration or empty");
        }

        // decode the JWT, which can throw a JWTDecodeException or UnprocessableEntityException
        $decoded = JWTProcessor::decode($token, $jwtPublicKey, $this->jwtAlgos, $this->jwtLeeway);
        $this->jwtPayload = (array) $decoded;
        $this->jwtValidated = true;
        return $this->jwtPayload;
    }
}
