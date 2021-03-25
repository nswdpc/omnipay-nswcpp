<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\RefundRequestException;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Represents a response to RefundRequest
 * @author James
 */
class RefundResponse extends AbstractResponse
{

    /**
     * Returns the refund reference in this response as a string
     */
    public function __toString() : string
    {
        if ($this->isSuccessful()) {
            return $this->data['refundReference'];
        } else {
            throw new RefundRequestException("No refund reference returned from the void request");
        }
    }

    /**
     * Get refundReference
     */
    public function getRefundReference()
    {
        return isset($this->data['refundReference']) ? $this->data['refundReference'] : false;
    }

    /**
     * Return whether the {@link Omnipay\NSWGOVCPP\RefundRequest} was successful
     */
    public function isSuccessful() : bool
    {
        return $this->getRefundReference() !== false;
    }
}
