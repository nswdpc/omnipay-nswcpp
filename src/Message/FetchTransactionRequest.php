<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\FetchTransactionRequestException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Get the status of a payment using its paymentReference
 *
 * @author James
 */
class FetchTransactionRequest extends AbstractAgencyRequest
{
    use GetterSetterParameterTrait;
    use NeedsAccessTokenTrait;

    /**
     * This request has no data to send
     */
    public function getData() {
        return [];
    }

    /**
     * To perform a payment status request, an access token is required
     * @param array $data
     * @throws FetchTransactionRequestException
     */
    public function sendData($data) : ResponseInterface
    {

        // validate the existence of the payment status URL
        $url = $this->getStatusUrl();
        if (!$url) {
            // TODO: validate the endpoint URL
            throw new FetchTransactionRequestException("Invalid statusUrl");
        }

        //  Get Payment Status  requires an Oauth2 access token
        $accessToken = $this->retrieveAccessToken();
        if (!$accessToken instanceof Accesstoken) {
            throw new FetchTransactionRequestException("Invalid access token for get payment status request");
        }

        if ($accessToken->isExpired()) {
            throw new FetchTransactionRequestException(
                "The access token for the payment status request is expired, request a new one",
                AccessTokenResponse::EXPIRED
            );
        }

        $result = $this->doGetRequest(
            $url,
            $headers = [
                'Authorization' => "Bearer " . $accessToken->getToken(),
            ],
            false
        );
        $response = new FetchTransactionResponse($this, $result);
        return $response;
    }
}
