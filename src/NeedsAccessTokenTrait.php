<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Trait used by requests that require an OAuth2 Access Token
 * in the CPP gateway
 * @author James
 */
trait NeedsAccessTokenTrait {
    /**
     * Retrieve and access token for authentication
     */
    public function retrieveAccessToken() : AccessToken {
        $this->validate('clientId','clientSecret','accessTokenUrl');
        $payload = [
            'grant_type' => self::OAUTH2_GRANT_CLIENT_CREDENTIALS,
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

        \NSWDPC\Payments\NSWGOVCPP\Agency\Logger::log("AccessToken: " . $accessToken->getToken());

        return $accessToken;
    }
}
