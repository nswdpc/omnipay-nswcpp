<?php

namespace OmniPay\NSWGOVCPP;

use OmniPay\NSWGOVCPP\RefundRequestException;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Represents a response to VoidRequest
 * @author James
 */
class VoidResponse extends AbstractResponse
{

    /**
     * Returns the access token in this response as a string
     */
    public function __toString() : string {
        if($this->isSuccessful()) {
            return $this->data['refundReference'];
        } else {
            throw new RefundRequestException("No refund reference returned from the void request");
        }
    }

    /**
     * Return whether the {@link NSWDPC\Payments\CPP\VoidRequest} was successful
     */
    public function isSuccessful() : boolean {
        return !empty($this->data['refundReference']);
    }

}
