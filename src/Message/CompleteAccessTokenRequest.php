<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\CompleteAccessTokenRequestException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Complete the access token request
 *
 * Give an access token response from the endpoint
 * validate and redirect to the payment gateway
 * @author James
 */
class CompleteAccessTokenRequest extends AbstractAgencyRequest
{

    use GetterSetterParameterTrait;

    public function setPayload(array $payload) {
        $this->setParameter('payload', $payload);
        return $this;
    }

    public function getPayload() : array {
        return $this->getParameter('payload');
    }

    /**
     * Validate the payment payload
     * @TODO implement per payment method payload requests - single payment, disbursement, recurring
     * @throws CompleteAccessTokenRequestException
     */
    public function validatePaymentPayload(array $payload = []) : bool {
        return true;
    }

    /**
     * Get payload data for the purchase request
     * @return array
     */
     public function getData() : array {
         $payload = $this->getPayload();
         $this->validatePaymentPayload();
         return $payload;
     }

     /**
      * Send access token request to the endpoint, handle the result
      * @param array $data
      * @throws CompleteAccessTokenRequestException
      */
    public function sendData($data) : ResponseInterface {

        // validate the existence of the payment request url
        $url = $this->getRequestPaymentUrl();
        if(!$url) {
            // TODO: validate the endpoint URL
            throw new CompleteAccessTokenRequestException("Invalid paymentRequestUrl");
        }

        // check for an empty payload data
        if(empty($data)) {
            throw new CompleteAccessTokenRequestException("Empty payment request payload");
        }

        $accessToken = $this->getAccessToken();
        if(!($accessToken instanceof AccessToken)) {
            throw new CompleteAccessTokenRequestException("Invalid access token");
        }

        if($accessToken->isExpired()) {
            throw new CompleteAccessTokenRequestException(
                "The access token has expired, request a new one",
                AccessTokenResponse::EXPIRED
            );
        }

        $result = $this->doPostRequest(
            $url,
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer " . $accessToken->getToken(),
            ],
            $data
        );
        $response = new CompleteAccessTokenResponse($this, $result);
        return $response;
    }
}
