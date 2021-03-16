<?php
namespace NSWDPC\Payments\CPP;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Represents an AccessToken Response from the endpoint
 * @author James
 */
class AccessTokenResponse extends AbstractResponse
{

    const EXPIRED = 'ACCESS_TOKEN_EXPIRED';

    /**
     * Returns the access token in this response as a string
     */
    public function __toString() : string {
        if($this->isSuccessful()) {
            return $this->data['access_token'];
        } else {
            throw new AccessTokenRequestException("No valid access token");
        }
    }

    /**
     * Return whether this access token has expired
     * @return boolean
     */
    public function isExpired() : boolean {
        return false;
    }

    /**
     * Return whether the {@link NSWDPC\Payments\CPP\AccessTokenRequest} was successful
     */
    public function isSuccessful() : boolean {
        return is_array($this->data)
            && !empty($this->data['access_token'])
            && (
                isset($this->data['token_type'])
                && $this->data['token_type'] == 'Bearer'
            )
            && (
                isset($this->data['expires_in'])
                && $this->data['expires_in'] > 0
            );
    }

}
