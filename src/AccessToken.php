<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Represents and Oauth2 access token received from the CPP gateway
 */
class AccessToken
{

    const EXPIRED = 'accesstoken-expired';

    const TYPE_BEARER = 'Bearer';

    /**
     * @var string
     */
    protected $token = '';

    /**
     * @var int
     */
    protected $expires = 0;

    /**
     * Expiry timestamp
     * @var int
     */
    protected $expiry = 0;

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @param string $token
     * @param int $expires lifetime in seconds
     * @param string $type
     * @param int $expiry timestamp, when 0 create from current timestamp
     */
    public function __construct(string $token, int $expires, string $type, int $expiry = 0)
    {
        $this->token = $token;
        $this->expires = $expires;
        $this->type = $type;
        if($expiry == 0) {
            $this->expiry = time() + $expires;
        } else {
            $this->expiry = $expiry;
        }
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
        return $this->type = self::TYPE_BEARER;
    }

    /**
     * Check whether the token has expired
     * Provide a leeway if a long running operation will use the token
     * for multiple requests, this marks the token as expired $leeway seconds
     * before it's recorded expiry timestamp
     * @param int $leeway
     */
    public function isExpired($leeway = 0) : bool
    {
        $expires_in = $this->expiry - $leeway - time();
        return $expires_in <= 0;
    }

    /**
     * Return the current expires value (lifetime) in seconds
     * @return int
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Return the current expiry timestamp
     * @return int
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * Return the current type
     * @return string
     */
    public function getType()
    {
        return $this->type;
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

    /**
     * Replace this token with the supplied token if this token has expired
     * The supplied token must not be expired
     * @return self
     * @param AccessToken $token
     */
    public function replaceIfExpired(AccessToken $token) : self {
        if($token->isValid() && !$this->isValid()) {
            $this->token = $token->getToken();
            $this->type = $token->getType();
            $this->expires = $token->getExpires();
            $this->expiry = time() + $this->expires;
        }
        return $this;
    }
}
