<?php

namespace OmniPay\NSWGOVCPP;

use OmniPay\NSWGOVCPP\CompleteAccessTokenRequestException;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Represents a response to the CompleteAccessTokenRequest
 * @author James
 */
class CompleteAccessTokenResponse extends AbstractResponse
{

    /**
     * Determines whether the purchase request represents a duplicate
     * <blockquote>If the agency has already send a request with the same transactionID and
     *  if the payment hasn't been collected then CPP will send the same CPP
     *  reference in the response with the flag as duplicate</blockquote>
     */
    public function isDuplicate() {
        if(empty($this->data) || !array_key_exists('duplicate', $this->data)) {
            throw new CompleteAccessTokenRequestException("You cannot call isDuplicate() when the response data is not available");
        }
        return $this->data['duplicate'] ? true : false;
    }

    /**
     * Return whether the {@link NSWDPC\Payments\CPP\CompleteAccessTokenRequest} was successful
     */
    public function isSuccessful() : boolean {
        return !empty($this->data['paymentReference']);
    }

    public function isRedirect() {
        return true;
    }

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        $url = "";
        $paymentReference = $this->data['paymentReference'];
        if($paymentReference) {
            $url = $this->parameters->get('gatewayUrl');
            $url .= http_build_query([
                'paymentReference' => $paymentReference
            ]);
        }
        return $url;
    }

    /**
     * Get the required redirect method (either GET or POST).
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * TODO: some checks and balances on the URL structure?
     */
    protected function validateRedirect()
    {
        $url = $this->getRedirectUrl();
        if(!$url) {
            throw new RuntimeException('This redirect URL is not valid.');
        }
    }

    public function getRedirectResponse() {
        $this->validateRedirect();
        $url = $this->getRedirectUrl();
        header("Location: {$url}");
    }

    /**
     * When the response is successful, redirect to the offsite gateway page
     */
    public function redirect() {
        if(!$this->isSuccessful()) {
            throw new CompleteAccessTokenRequestException("You cannot redirect when the request was not successful");
        }
        $this->getRedirectResponse();
        exit;
    }

}
