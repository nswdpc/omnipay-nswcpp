<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\Common\AbstractGateway;
use Omnipay\NSWGOVCPP\AccessTokenRequest;
use Omnipay\NSWGOVCPP\CompleteAccessTokenRequest;
use Omnipay\NSWGOVCPP\CompletePurchaseRequest;
use Omnipay\NSWGOVCPP\FetchTransactionRequest;
use Omnipay\NSWGOVCPP\DailyReconciliationRequest;
use Omnipay\NSWGOVCPP\RefundRequest;

/**
 * Represents a gateway to handle communication with the CPP
 *
 * @author James
 */
class Gateway extends AbstractGateway
{
    use GetterSetterParameterTrait;

    public function getName()
    {
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
     * Start the purchase process
     * Create an access token, send the payment payload to the endpoint, redirect to the gateway
     * @return Omnipay\NSWGOVCPP\CompleteAccessTokenRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * Complete a purchase
     * The gateway will POST a JWT token that is decoded, containing the payment details
     * @return Omnipay\NSWGOVCPP\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * Fetch a transaction, get its status
     * @return Omnipay\NSWGOVCPP\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $parameters);
    }

    /**
     * Refund a payment reference, using an access token
     * @return Omnipay\NSWGOVCPP\RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    public function dailyReconciliation(array $parameters = [])
    {
        return $this->createRequest(DailyReconciliationRequest::class, $parameters);
    }
}
