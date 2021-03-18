<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Trait used by Gateway and Requests to get/set default parameters
 * in the CPP gateway
 * @author James
 */
trait GetterSetterParameterTrait {

    public function setClientId($clientId) {
        $this->setParameter('clientId', $clientId);
        return $this;
    }

    public function getClientId() {
        return $this->getParameter('clientId');
    }

    public function setClientSecret($clientSecret) {
        $this->setParameter('clientSecret', $clientSecret);
        return $this;
    }

    public function getClientSecret() {
        return $this->getParameter('clientSecret');
    }

    public function setJwtSecret($jwtSecret) {
        $this->setParameter('jwtSecret', $jwtSecret);
        return $this;
    }

    public function getJwtSecret() {
        return $this->getParameter('jwtSecret');
    }

    public function setJwtPublicKey($jwtPublicKey) {
        $this->setParameter('jwtPublicKey', $jwtPublicKey);
        return $this;
    }

    public function getJwtPublicKey() {
        return $this->getParameter('jwtPublicKey');
    }

    public function setAccessTokenUrl($accessTokenUrl) {
        $this->setParameter('accessTokenUrl', $accessTokenUrl);
        return $this;
    }

    public function getAccessTokenUrl() {
        $url = $this->getParameter('accessTokenUrl');
        return $url;
    }

    public function setAccessToken(AccessToken $accessToken) {
        $this->setParameter('accessToken', $accessToken);
        return $this;
    }

    public function getAccessToken() {
        return $this->getParameter('accessToken');
    }

    public function setRequestPaymentUrl($requestPaymentUrl) {
        $this->setParameter('requestPaymentUrl', $requestPaymentUrl);
        return $this;
    }

    public function getRequestPaymentUrl() {
        return $this->getParameter('requestPaymentUrl');
    }

    public function setGatewayUrl($gatewayUrl) {
        $this->setParameter('gatewayUrl', $gatewayUrl);
        return $this;
    }

    public function getGatewayUrl() {
        return $this->getParameter('gatewayUrl');
    }

    public function setRefundUrl($refundUrl) {
        $this->setParameter('refundUrl', $refundUrl);
        return $this;
    }

    public function getRefundUrl() {
        return $this->getParameter('refundUrl');
    }

}
