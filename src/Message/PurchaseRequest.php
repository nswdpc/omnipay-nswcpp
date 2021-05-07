<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Represents a purchase request to the CPP
 *
 * Retrieves an access token from the endpoint, for use in purchase requests
 * @author James
 */
class PurchaseRequest extends AbstractAgencyRequest
{
    use GetterSetterParameterTrait;
    use NeedsAccessTokenTrait;

    /**
     * Set the payment payload
     */
    public function setPayload(array $payload)
    {
        $this->setParameter('payload', $payload);
        return $this;
    }

    /**
     * Get the payment payload
     */
    public function getPayload() : array
    {
        return $this->getParameter('payload');
    }

    /**
     * Validate the payment payload
     * @TODO implement per payment method payload requests - single payment, disbursement, recurring
     * @throws PurchaseRequestException
     */
    public function validatePaymentPayload(array $payload = []) : bool
    {
        return true;
    }

    /**
     * Get payload data for the purchase request
     * @return array
     */
    public function getData() : array
    {
        $payload = $this->getPayload();
        $this->validatePaymentPayload();
        return $payload;
    }

    /**
     * Send payment payload to the endpoint, handle the response
     * Authenticate with the access token
     * @param array $data
     * @throws CompleteAccessTokenRequestException
     */
    public function sendData($data) : ResponseInterface
    {

        // validate the existence of the payment request url
        $url = $this->getRequestPaymentUrl();
        if (!$url) {
            // TODO: validate the endpoint URL
            throw new PurchaseRequestException("Invalid paymentRequestUrl");
        }

        // check for an empty payload data
        if (empty($data)) {
            throw new PurchaseRequestException("Empty payment request payload");
        }

        $accessToken = $this->retrieveAccessToken();
        if (!($accessToken instanceof AccessToken)) {
            throw new PurchaseRequestException("Invalid access token");
        }

        $token = $accessToken->getToken();
        if (empty($token)) {
            throw new PurchaseRequestException("Empty access token");
        }

        $result = $this->doPostRequest(
            $url,
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer " . $token,
            ],
            $data
        );

        if(isset($result['fault'])) {
            $faultstring = $result['fault']['faultstring'] ?: 'not supplied';
            throw new PurchaseRequestException("Response contained fault: {$faultstring}");
        }

        $response = new PurchaseResponse($this, $result);
        return $response;
    }
}
