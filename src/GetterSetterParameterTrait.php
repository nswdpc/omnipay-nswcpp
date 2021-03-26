<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Trait used by Gateway and Requests to get/set default parameters
 * in the CPP gateway
 * @author James
 */
trait GetterSetterParameterTrait
{
    public function setClientId($clientId)
    {
        $this->setParameter('clientId', $clientId);
        return $this;
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientSecret($clientSecret)
    {
        $this->setParameter('clientSecret', $clientSecret);
        return $this;
    }

    public function getClientSecret()
    {
        return $this->getParameter('clientSecret');
    }

    public function setJwtPublicKey($jwtPublicKey)
    {
        $this->setParameter('jwtPublicKey', $jwtPublicKey);
        return $this;
    }

    public function getJwtPublicKey()
    {
        return $this->getParameter('jwtPublicKey');
    }

    public function setAccessTokenUrl($accessTokenUrl)
    {
        $this->setParameter('accessTokenUrl', $accessTokenUrl);
        return $this;
    }

    public function getAccessTokenUrl()
    {
        $url = $this->getParameter('accessTokenUrl');
        return $url;
    }

    public function setAccessToken(AccessToken $accessToken)
    {
        $this->setParameter('accessToken', $accessToken);
        return $this;
    }

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setRequestPaymentUrl($requestPaymentUrl)
    {
        $this->setParameter('requestPaymentUrl', $requestPaymentUrl);
        return $this;
    }

    public function getRequestPaymentUrl()
    {
        return $this->getParameter('requestPaymentUrl');
    }

    public function setGatewayUrl($gatewayUrl)
    {
        $this->setParameter('gatewayUrl', $gatewayUrl);
        return $this;
    }

    public function getGatewayUrl()
    {
        return $this->getParameter('gatewayUrl');
    }

    public function setRefundUrl($refundUrl)
    {
        $this->setParameter('refundUrl', $refundUrl);
        return $this;
    }

    /**
     * @note the URL is in the format https://api-psm.g.testservicensw.net/cpp-digital/api/payments/{{paymentReference}}/refund
     * The paymentReference must be passed in for the str_replace to occur
     */
    public function getRefundUrl()
    {
        $url = $this->getParameter('refundUrl');
        $paymentReference = $this->getPaymentReference();
        if (!$paymentReference) {
            throw new \Exception("Internal error: the paymentReference was not provided");
        }
        $url = str_replace("{{paymentReference}}", urlencode($paymentReference), $url);
        return $url;
    }

    public function setPaymentReference($paymentReference)
    {
        $this->setParameter('paymentReference', $paymentReference);
        return $this;
    }

    public function getPaymentReference()
    {
        return $this->getParameter('paymentReference');
    }

    /**
     * Set the status URL used to retrieve transaction status
     */
    public function setStatusUrl($statusUrl)
    {
        $this->setParameter('statusUrl', $statusUrl);
        return $this;
    }

    /**
     * @note the URL is in the format
     * https://api-psm.g.testservicensw.net/cpp-digital/api/payments/{{CPP-Reference}}/status
     * The paymentReference must be passed in for the str_replace to occur
     */
    public function getStatusUrl()
    {
        $url = $this->getParameter('statusUrl');
        $paymentReference = $this->getPaymentReference();
        if (!$paymentReference) {
            throw new \Exception("Internal error: the paymentReference was not provided");
        }
        $url = str_replace("{{paymentReference}}", urlencode($paymentReference), $url);
        return $url;
    }

}
