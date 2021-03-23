<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Represents and Oauth2 access token received from the CPP gateway
 */
class AccessToken
{

    /**
     * @var string
     */
    protected $token = '';

    /**
     * @var int
     */
    protected $expires = 0;

    /**
     * @var string
     */
    protected $type = '';

    public function __construct($token, $expires, $type)
    {
        $this->token = $token;
        $this->expires = $expires;
        $this->type = $type;
    }

    /*
     * Return the token
     */
    public function getToken() : string
    {
        return $this->token;
    }

    public function isBearerType() : bool
    {
        return $this->type = "Bearer";
    }

    /**
     * Check whether the token has expired
     * @param int $leeway
     */
    public function isExpired($leeway = 0) : bool
    {
        return time() > ($this->expires - $leeway);
    }

    /**
     * Return the current expires
     * @return int
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Used by tests to fudge an expiry
     * @param int $expires
     * @return void
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * Return whether valid
     */
    public function isValid($leeway = 0) : bool
    {
        return $this->getToken() && !$this->isExpired($leeway) && $this->isBearerType();
    }
}
