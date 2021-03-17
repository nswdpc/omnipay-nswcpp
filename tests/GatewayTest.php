<?php

namespace OmniPay\NSWGOVCPP\Tests;

use Omnipay\Tests\GatewayTestCase;
use OmniPay\NSWGOVCPP\Gateway;
use OmniPay\NSWGOVCPP\Exception\AccessTokenRequestException;
use OmniPay\NSWGOVCPP\Exception\CompleteAccessTokenRequestException;
use OmniPay\NSWGOVCPP\Exception\CompletePurchaseRequestException;
use OmniPay\NSWGOVCPP\Exception\RefundRequestException;
use OmniPay\NSWGOVCPP\Exception\PaymentCompletionException;
use OmniPay\NSWGOVCPP\Exception\UnprocessableEntityException;
use OmniPay\NSWGOVCPP\Message\AccessTokenRequest;
use OmniPay\NSWGOVCPP\Message\CompleteAccessTokenRequest;
use OmniPay\NSWGOVCPP\Message\CompletePurchaseRequest;
use OmniPay\NSWGOVCPP\Message\RefundRequest;

class GatewayTest extends GatewayTestCase {

    /** @var  Gateway */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    protected function getTestParams() {
        return [
            'clientId' => 'testClientId',
            'clientSecret' => 'testClientSecret',
            'jwtSecret' => 'testJWTSecret',
            'accessTokenUrl' => 'https://localhost/accesstoken',
            'requestPaymentUrl' => 'https://localhost/paymentrequest',
            'gatewayUrl' => 'https://localhost/gateway',
            'refundUrl' => 'https://localhost/refund'
        ];
    }

    public function testAuthorise()
    {
        $params = $this->getTestParams();

        $request = $this->gateway->authorize([
            'clientId' => $params['clientId'],
            'clientSecret' => $params['clientSecret'],
            'accessTokenUrl' => $params['accessTokenUrl']
        ]);
        $this->assertInstanceOf(AccessTokenRequest::class, $request);
    }

}
