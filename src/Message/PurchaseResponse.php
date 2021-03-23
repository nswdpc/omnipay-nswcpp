<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\NSWGOVCPP\CompleteAccessTokenRequestException;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Represents a response to the PurchaseRequest
 * @author James
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    /**
     * Determines whether the purchase request represents a duplicate
     * <blockquote>If the agency has already send a request with the same transactionID and
     *  if the payment hasn't been collected then CPP will send the same CPP
     *  reference in the response with the flag as duplicate</blockquote>
     * @throws PurchaseRequestException
     */
    public function isDuplicate()
    {
        if (empty($this->data) || !array_key_exists('duplicate', $this->data)) {
            throw new PurchaseRequestException("You cannot call isDuplicate() when the response data is not available");
        }
        return $this->data['duplicate'] ? true : false;
    }

    /**
     * Get paymentReference
     */
    public function getPaymentReference()
    {
        return isset($this->data['paymentReference']) ? $this->data['paymentReference'] : false;
    }

    /**
     * Return whether the {@link NSWDPC\Payments\CPP\CompleteAccessTokenRequest} was successful
     */
    public function isSuccessful() : bool
    {
        return $this->getPaymentReference() !== false;
    }

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        $url = "";
        $paymentReference = $this->getPaymentReference();
        $gatewayUrl = $this->getRequest()->getGatewayUrl();
        if ($paymentReference && $gatewayUrl) {
            $url = rtrim($gatewayUrl, "?");
            $url .= "?";
            $url .= http_build_query([
                'paymentReference' => $paymentReference
            ]);
        }
        return $url;
    }

    /**
     * The purchase response will redirect to the Gateway for completion
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return true;
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
     * Send a header to redirect the browser to the CPP gateway
     * Helper method to call a redirect immedidately using header()
     * @return void;
     */
    public function doRedirectToGateway()
    {
        $url = $this->getRedirectUrl();
        if ($url) {
            header("HTTP/1.1 302 Found");
            header("Location: " . $url);
            exit;
        }
    }

    /**
     * @return RedirectResponse
     */
    public function getRedirectResponse()
    {
        $this->validateRedirect();
        $headers = [];
        return new RedirectResponse($this->getRedirectUrl(), 302, $headers);
    }
}
