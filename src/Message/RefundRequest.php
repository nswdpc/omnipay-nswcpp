<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\RefundRequestException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Refund a previous payment using its paymentReference and an amount
 *
 * @author James
 */
class RefundRequest extends AbstractAgencyRequest
{
    use GetterSetterParameterTrait;
    use NeedsAccessTokenTrait;

    public function setRefundAmount($amount)
    {
        $this->setParameter('refundAmount', $amount);
    }

    public function getRefundAmount()
    {
        return $this->getParameter('refundAmount');
    }

    public function setRefundReason($reason)
    {
        $this->setParameter('refundReason', $reason);
    }

    public function getRefundReason()
    {
        return $this->getParameter('refundReason');
    }

    /**
     * Get payload data for the refund request
     * @return array
     */
    public function getData() : array
    {
        return [
            'amount' => $this->getRefundAmount(),
            'refundReason' => $this->getRefundReason()
        ];
    }

    /**
     * To perform a refund, an access token is required
     * @param array $data
     * @throws RefundRequestException
     */
    public function sendData($data) : ResponseInterface
    {

        // validate the existence of the refund URL
        $url = $this->getRefundUrl();
        if (!$url) {
            // TODO: validate the endpoint URL
            throw new RefundRequestException("Invalid refundUrl");
        }

        // check for an empty payload data
        if (empty($data)) {
            throw new RefundRequestException("Empty refund request payload");
        }

        // verify amount is sensible, must be >=0
        $amount = $data['amount'];
        if (!is_float($amount) && !is_integer($amount) && $amount <= 0) {
            throw new RefundRequestException("Invalid refund amount: {$amount}, " . gettype($amount));
        }

        // Refund requires an Oauth2 access token
        $accessToken = $this->retrieveAccessToken();
        if (!$accessToken instanceof Accesstoken) {
            throw new RefundRequestException("Invalid access token for refund request");
        }

        if ($accessToken->isExpired()) {
            throw new RefundRequestException(
                "The access token for the refund request is expired, request a new one",
                AccessToken::EXPIRED
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
        $response = new RefundResponse($this, $result);
        return $response;
    }
}
