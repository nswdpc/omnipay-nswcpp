<?php

namespace OmniPay\NSWGOVCPP\Message;

use OmniPay\NSWGOVCPP\Exception\CompleteAccessTokenRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Complete the access token request
 *
 * Give an access token response from the endpoint
 * validate and redirect to the payment gateway
 * @author James
 */
class CompleteAccessTokenRequest extends AbstractRequest
{

    /**
     * Whether the purchase reqest params were validate successfully
     * @var boolean
     */
    protected $validated = false;


    /**
     * Initialise the purchase request
     */
    public function initialize(array $parameters = array())
    {

        // valudate the URLs used
        if(empty($parameters['endpointUrl'])) {
            // TODO: validate the endpoint URL
            throw new CompleteAccessTokenRequestException("Invalid endpoint URL");
        }
        if(empty($parameters['gatewayUrl'])) {
            // TODO: validate the gateway URL
            throw new CompleteAccessTokenRequestException("Invalid payment gateway URL");
        }

        // validate the payload
        if(empty($parameters['payload'])) {
            throw new CompleteAccessTokenRequestException("Empty payment payload");
        } else {
            $this->validatePaymentPayload($parameters['payload']);
        }

        if(empty($parameters['accessToken']) || !($parameters['accessToken'] instanceof AccessTokenResponse)) {
            throw new CompleteAccessTokenRequestException("Invalid access token");
        }

        if($parameters['accessToken']->isExpired()) {
            throw new CompleteAccessTokenRequestException("The access token has expired, request a new one", AccessTokenResponse::EXPIRED);
        }

        $this->validated = true;

        return parent::initialize($parameters);
    }

    /**
     * Validate the payment payload
     * @TODO implement per payment method payload requests - single payment, disbursement, recurring
     * @throws CompleteAccessTokenRequestException
     */
    public function validatePaymentPayload(array $payload) : boolean {
        return true;
    }

    /**
     * Get payload data for the purchase request
     * @return array
     */
     public function getData() : array {
         $payload = $this->parameters->get('payload');
         return $payload;
     }

     /**
      * Send access token request to the endpoint, handle the result
      * @param array $data
      * @throws CompleteAccessTokenRequestException
      */
    public function sendData($data) : ResponseInterface {

        if(!$this->validated) {
            throw new CompleteAccessTokenRequestException("You cannot call sendData() without validating the parameters.");
        }

        $response = $this->httpClient->post(
            $this->endpointUrl,
            [
                'Authorization' => "Bearer " . $this->parameters->get('accessToken'),/* @var AccessTokenResponse */
                'Content-Type' => 'application/json',
            ],
            $data
        )->send();

        // create the purchase response
        $response = new CompleteAccessTokenResponse($this, $response->json());
        return $response;
    }
}
