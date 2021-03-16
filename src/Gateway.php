<?php

namespace NSWDPC\Payments\CPP;

use Omnipay\Common\AbstractGateway;
use NSWDPC\Payments\CPP\Message\AccessTokenRequest;
use NSWDPC\Payments\CPP\Message\CompleteAccessTokenRequest;
use NSWDPC\Payments\CPP\Message\CompletePurchaseRequest;
use NSWDPC\Payments\CPP\Message\RefundRequest;

/**
 * Represents a gateway to handle communication with the CPP
 *
 * @author James
 */
class Gateway extends AbstractGateway
{

    public function getName() {
        return 'NSWGOV CPP';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'clientId' => '',
            'clientSecret' => '',
            'jwtSecret' => '',
            'accessTokenUrl' => '',
            'requestPaymentUrl' => '',
            'gatewayUrl' => '',
            'refundUrl' => ''
        ];
    }

    /**
     * Get an access token that can be used for purchase/void requests
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest(AccessTokenRequest::class, $parameters);
    }

    /**
     * Complete the authorisation process
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest(CompleteAccessTokenRequest::class, $parameters);
    }

    /**
     * Complete a purchase
     * The gateway will POST the payment details together with a JWT token to verify
     * the completion call is valid
     * completePurchase only validates the JWT token
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * Refund a payment reference, using an access token
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

}
