<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Authorise request for the CPP
 *
 * Retrieves an access token from the endpoint, for use in purchase requests
 * @author James
 */
class AccessTokenRequest extends AbstractAgencyRequest
{

    use GetterSetterParameterTrait;

    const OAUTH2_GRANT_CLIENT_CREDENTIALS = 'client_credentials';

    /**
     * Get payload data for the request
     * @return array
     */
     public function getData() : array {
         $this->validate('clientId','clientSecret','accessTokenUrl');
         return [
             'grant_type' => self::OAUTH2_GRANT_CLIENT_CREDENTIALS,
             'client_id' => $this->getParameter('clientId'),
             'client_secret' => $this->getParameter('clientSecret'),
         ];
     }

     /**
      * @TODO implement session based saving of access token
      * Check if the current token has expired, if so, return false
      * @return mixed false when the token has expired, an AccessTokenResponse if not
      */
     public function getCurrentAccessToken() {
         return false;
     }

     /**
      * Send access token request to the endpoint, return the result
      * @param array $data
      */
    public function sendData($data) : ResponseInterface {
        $response = $this->getCurrentAccessToken();
        if(!$response || $response->isExpired()) {
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
                $data
            );
            // result contains: access_token, expires, token_type
            $response = new AccessTokenResponse($this, $result);
        }
        return $response;
    }
}
