<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\Common\AbstractGateway;
use Omnipay\NSWGOVCPP\AccessTokenRequest;
use Omnipay\NSWGOVCPP\CompleteAccessTokenRequest;
use Omnipay\NSWGOVCPP\CompletePurchaseRequest;
use Omnipay\NSWGOVCPP\RefundRequest;

/**
 * Represents a gateway to handle communication with the CPP
 *
 * @author James
 */
class Gateway extends AbstractGateway
{

    use GetterSetterParameterTrait;

    public function getName() {
        return "NSWGOVCPP";
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return ParameterStorage::getAll();
    }

    /**
     * Get an access token that can be used for purchase/void requests
     * @return Omnipay\NSWGOVCPP\AccessTokenRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest(AccessTokenRequest::class, $parameters);
    }

    /**
     * Complete the authorisation process
     * This sends the payment request to the endpoint along with the OAuth2 access token
     * @return Omnipay\NSWGOVCPP\CompleteAccessTokenRequest
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest(CompleteAccessTokenRequest::class, $parameters);
    }

    /**
     * Complete a purchase
     * The gateway will POST a JWT token that is decoded, containing the payment details
     * @return Omnipay\NSWGOVCPP\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * Refund a payment reference, using an access token
     * @return Omnipay\NSWGOVCPP\RefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

}
