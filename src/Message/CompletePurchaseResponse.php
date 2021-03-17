<?php

namespace OmniPay\NSWGOVCPP;

use OmniPay\NSWGOVCPP\CompletePurchaseRequestException;
use OmniPay\NSWGOVCPP\UnprocessableEntityException;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Represents a response to CompletePurchaseRequest
 * @author James
 */
class CompletePurchaseResponse extends AbstractResponse
{

    /**
     * Return whether the {@link NSWDPC\Payments\CPP\CompletePurchaseRequest} was successful
     * This validates the existence of common data in the JWT payload
     * @throws CompletePurchaseRequestException
     */
    public function hasValidBaseData() : boolean {
        $required = [
            'paymentReference',
            'paymentMethod',
            'amount',
            'paymentCompletionReference',
            'agencyTransactionId'
        ];
        $missing = [];
        foreach($required as $key) {
            if(empty($this->data[$key])) {
                $missing[] = $key;
            }
        }
        $valid = empty($missing);
        if(!$valid) {
            throw new CompletePurchaseRequestException("Missing: " . implode(",", $missing));
        }
        return true;
    }

    /**
     * Complete the payment completion processing
     * The application should provide a callback that returns a boolean or
     * throws an Exception or an UnprocessableEntityException
     * @todo use HTTP library to send header and exit
     */
    public function complete( callable $callback ) {
        try {
            // check for validate common payment data
            $this->hasValidBaseData();
            // verify via the callable provided by the application
            if($result = $callback($this)) {
                // All OK
                header("HTTP/1.1 200 OK");
                exit;
            } else {
                // Not ok
                throw new \Exception("Could not complete payment resolution");
            }
        } catch (UnprocessableEntityException $e) {
            header("HTTP/1.1 422 Unprocessable Entity");
            exit;
        } catch (\Exception $e) {
            header("HTTP/1.1 503 Service Unavailable");
            exit;
        }
    }

}
