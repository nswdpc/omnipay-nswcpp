<?php
namespace NSWDPC\Payments\CPP;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Authorise request for the CPP
 *
 * Retrieves an access token from the endpoint, for use in purchase requests
 * @author James
 */
class AccessTokenRequest extends AbstractRequest
{

    /**
     * Get payload data for the request
     * @return array
     */
     public function getData() : array {
         $this->validate('client_id', 'client_secret');
         return [
             'grant_type' => 'client_credentials',
             'client_id' => $this->parameters->get('clientId'),
             'client_secret' => $this->parameters->get('clientSecret'),
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
            // the token is set currently set or has expired
            $tokenData = $this->httpClient->post(
                $this->parameters->get('endpointUrl'),
                [
                    'Content-Type' => 'application/json',
                ],
                $data
            )->send();
            $response = new AccessTokenResponse($this, $tokenData->json());
        }
        return $response;
    }
}
