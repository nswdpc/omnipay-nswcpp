<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Trait used by requests that require an OAuth2 Access Token
 * in the CPP gateway
 * @author James
 */
trait NeedsAccessTokenTrait {

    protected function getOAuth2GrantType() {
        return 'client_credentials';
    }

    /**
     * @TODO implement session/server based saving of access token
     * Check if the current token has expired, if so, return false
     * @return mixed false when the token has expired, an AccessTokenResponse if not
     */
    public function getCurrentAccessToken() {
        return false;
    }

    /**
     * Retrieve and access token for authentication
     */
    public function retrieveAccessToken() : AccessToken {
        $this->validate('clientId','clientSecret','accessTokenUrl');
        $payload = [
            'grant_type' => $this->getOAuth2GrantType(),
            'client_id' => $this->getParameter('clientId'),
            'client_secret' => $this->getParameter('clientSecret'),
        ];

        $accessToken = $this->getCurrentAccessToken();/* @var AccessToken|false */
        if(!$accessToken || $accessToken->isExpired()) {
            $url = $this->getAccessTokenUrl();
            if(!$url) {
                throw new AccessTokenRequestException("The accessTokenUrl is invalid");
            }
            // the token is set currently set or has expired
            $result = $this->doPostRequest(
                $url,
                $headers = [
                    'Content-Type' => 'application/json',
                ],
                $payload
            );
            $accessToken = new AccessToken(
                isset($result['access_token']) ? $result['access_token'] : '',
                isset($result['expires']) ? $result['expires'] : '',
                isset($result['token_type']) ? $result['token_type'] : ''
            );
        }

        return $accessToken;
    }
}