<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\FetchTransactionRequestException;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Represents a response to FetchTransactionRequest
 * @author James
 */
class FetchTransactionResponse extends AbstractResponse
{

    /**
     * Get paymentReference
     */
    public function getPaymentReference()
    {
        return isset($this->data['paymentReference']) ? $this->data['paymentReference'] : false;
    }

    /**
     * Get paymentStatus
     */
    public function getPaymentStatus()
    {
        return isset($this->data['paymentStatus']) ? $this->data['paymentStatus'] : false;
    }

    /**
     * Return whether the {@link Omnipay\NSWGOVCPP\FetchTransactionRequest} was successful
     */
    public function isSuccessful() : bool
    {
        return $this->getPaymentStatus() !== false;
    }
}
