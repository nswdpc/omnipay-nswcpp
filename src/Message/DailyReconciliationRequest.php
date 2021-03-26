<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\DailyReconciliationRequestException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Set up the Daily Reconciliation request
 *
 * @author James
 */
class DailyReconciliationRequest extends AbstractAgencyRequest
{
    use GetterSetterParameterTrait;
    use NeedsAccessTokenTrait;

    /**
     * Set the reconciliation date
     */
    public function setReconciliationDate(\DateTime $date) {
        $this->setParameter('reconciliationDate', $date);
        return $this;
    }

    /**
     * Get the reconciliation date
     */
    public function getReconciliationDate() : \DateTime {
        return $this->getParameter('reconciliationDate');
    }

    /**
     * This request has no data to send
     */
    public function getData() {
        return [];
    }

    /**
     * To perform a payment status request, an access token is required
     * @param array $data
     * @throws DailyReconciliationRequestException
     */
    public function sendData($data) : ResponseInterface
    {

        // validate the existence of the reconciliation URL
        $url = $this->getDailyReconciliationUrl();
        if (!$url) {
            // TODO: validate the endpoint URL
            throw new DailyReconciliationRequestException("Invalid statusUrl");
        }
        $datetime = $this->getReconciliationDate();
        $date = $datetime->format('Y-m-d');
        $url = $url . "?reportDate=" . urlencode($date);

        //  Get Payment Status  requires an Oauth2 access token
        $accessToken = $this->retrieveAccessToken();
        if (!$accessToken instanceof Accesstoken) {
            throw new DailyReconciliationRequestException("Invalid access token for reconciliation request");
        }

        if ($accessToken->isExpired()) {
            throw new DailyReconciliationRequestException(
                "The access token for the reconciliation request is expired, request a new one",
                AccessToken::EXPIRED
            );
        }

        // process the text/csv results
        try {
            // send the request
            $output = $this->doGetRequest(
                $url,
                $headers = [
                    'Authorization' => "Bearer " . $accessToken->getToken(),
                    'Accept' => 'text/csv,text/plain'
                ],
                true
            );
        } catch (\Exception $e) {
            $output = false;
        }
        $data = [
            'reconciliationReport' => $output
        ];
        $response = new DailyReconciliationResponse($this, $data);
        return $response;
    }
}
