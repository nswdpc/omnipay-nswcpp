<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\AccessTokenRequestException;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Represents an AccessToken Response from the endpoint
 * @author James
 */
class AccessTokenResponse extends AbstractResponse
{

    const EXPIRED = 'ACCESS_TOKEN_EXPIRED';

    /**
     * Return the access token string, which is set from the result of an AccessTokenRequest
     * @return string
     */
    public function getAccessToken() {
        $token = new AccessToken(
            isset($this->data['access_token']) ? $this->data['access_token'] : '',
            isset($this->data['expires']) ? $this->data['expires'] : '',
            isset($this->data['token_type']) ? $this->data['token_type'] : ''
        );
        return $token;
    }

    /**
     * Return whether the {@link NSWDPC\Payments\CPP\AccessTokenRequest} was successful
     */
    public function isSuccessful() : bool {
        $token = $this->getAccessToken();
        return $token->isValid();
    }

}
