<?php

namespace Omnipay\NSWGOVCPP\Tests;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\NSWGOVCPP\Gateway;
use Omnipay\NSWGOVCPP\AccessTokenRequestException;
use Omnipay\NSWGOVCPP\CompleteAccessTokenRequestException;
use Omnipay\NSWGOVCPP\CompletePurchaseRequestException;
use Omnipay\NSWGOVCPP\RefundRequestException;
use Omnipay\NSWGOVCPP\PaymentCompletionException;
use Omnipay\NSWGOVCPP\UnprocessableEntityException;
use Omnipay\NSWGOVCPP\AccessTokenRequest;
use Omnipay\NSWGOVCPP\CompleteAccessTokenRequest;
use Omnipay\NSWGOVCPP\CompletePurchaseRequest;
use Omnipay\NSWGOVCPP\RefundRequest;

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
