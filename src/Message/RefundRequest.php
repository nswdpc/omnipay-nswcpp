<?php
namespace NSWDPC\Payments\CPP;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Refund a previous payment using its paymentReference and an amount
 *
 * @author James
 */
class RefundRequest extends AbstractRequest
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
            throw new RefundRequestException("Invalid endpoint URL");
        }

        // validate the payload
        if(empty($parameters['amount'])) {

            throw new RefundRequestException("Empty refund amount");
        }

        if(empty($parameters['accessToken']) || !($parameters['accessToken'] instanceof AccessTokenResponse)) {
            throw new RefundRequestException("Invalid access token");
        }

        if($parameters['accessToken']->isExpired()) {
            throw new RefundRequestException("The access token has expired, request a new one", AccessTokenResponse::EXPIRED);
        }

        $this->validated = true;

        return parent::initialize($parameters);
    }

    /**
     * Get payload data for the refund request
     * @return array
     */
     public function getData() : array {
         return [
             'amount' => $this->parameters->get('amount'),
             'refundReason' => $this->parameters->get('refundReason'),
         ];
     }

     /**
      * Send access token request to the endpoint, handle the result
      * @param array $data
      * @throws RefundRequestException
      */
    public function sendData($data) : ResponseInterface {

        if(!$this->validated) {
            throw new RefundRequestException("You cannot call sendData() without validating the parameters.");
        }

        $response = $this->httpClient->post(
            $this->endpointUrl,
            [
                'Authorization' => "Bearer " . $this->parameters->get('accessToken'),/* @var AccessTokenResponse */
                'Content-Type' => 'application/json',
            ],
            $data
        )->send();
        $response = new RefundResponse($this, $response->json());
        return $response;
    }
}
