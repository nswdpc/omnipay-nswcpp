<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Trait used by requests that require an OAuth2 Access Token
 * in the CPP gateway
 * @author James
 */
trait NeedsAccessTokenTrait
{

    /**
     * @var AccessToken
     */
    protected $accessToken;

    protected function getOAuth2GrantType()
    {
        return 'client_credentials';
    }

    /**
     * Get the current AccessToken
     * @return AccessToken|null
     */
    public function getCurrentAccessToken()
    {
        if ($this->accessToken && $this->accessToken->isValid()) {
            return $this->accessToken;
        } else {
            return null;
        }
    }

    /**
     * Setter for accessToken. This can be used by tests to set a mock access token
     * @param AccessToken a valid access token
     */
    public function setCurrentAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Retrieve and access token for authentication
     * @return AccessToken|false
     */
    public function retrieveAccessToken()
    {
        $this->validate('clientId', 'clientSecret', 'accessTokenUrl');
        $payload = [
            'grant_type' => $this->getOAuth2GrantType(),
            'client_id' => $this->getParameter('clientId'),
            'client_secret' => $this->getParameter('clientSecret'),
        ];

        $accessToken = $this->getCurrentAccessToken();/* @var AccessToken|false */
        if (!$accessToken || !$accessToken->isValid()) {
            $url = $this->getAccessTokenUrl();
            if (!$url) {
                throw new AccessTokenRequestException("The accessTokenUrl is invalid");
            }

            // the token is set currently set or has expired
            $result = $this->doPostRequest(
                $url,
                $headers = [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                $payload
            );

            if(isset($result['error'])) {
                $error = $result['error'] ?: '';
                $description = $result['error_description'] ?: '';
                throw new AccessTokenRequestException("Response contained error: {$error}/{$description}");
            }

            // Access Token, expiring from current timestamp
            $accessToken = new AccessToken(
                isset($result['access_token']) ? $result['access_token'] : '',
                isset($result['expires_in']) ? intval($result['expires_in']) : 0,
                isset($result['token_type']) ? $result['token_type'] : ''
            );
            $this->setCurrentAccessToken( $accessToken );

        }
        return $accessToken;
    }
}
