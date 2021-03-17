<?php

namespace OmniPay\NSWGOVCPP\Tests;

use Omnipay\Tests\GatewayTestCase;
use OmniPay\NSWGOVCPP\Gateway;
use OmniPay\NSWGOVCPP\AccessTokenRequestException;
use OmniPay\NSWGOVCPP\CompleteAccessTokenRequestException;
use OmniPay\NSWGOVCPP\CompletePurchaseRequestException;
use OmniPay\NSWGOVCPP\RefundRequestException;
use OmniPay\NSWGOVCPP\PaymentCompletionException;
use OmniPay\NSWGOVCPP\UnprocessableEntityException;
use OmniPay\NSWGOVCPP\AccessTokenRequest;
use OmniPay\NSWGOVCPP\CompleteAccessTokenRequest;
use OmniPay\NSWGOVCPP\CompletePurchaseRequest;
use OmniPay\NSWGOVCPP\RefundRequest;

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
