<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\Omnipay;

/**
 * Factory class used to create a Gateway instance
 *
 * @author James
 */
class Factory
{

    /**
     * Create a new instance of {@link NSWDPC\Payments\CPP\Gateway} and return it
     * @param string $clientId
     * @param string $clientSecret
     * @param string $jwtSecret
     * @param string $accessTokenUrl
     * @param string $paymentRequestUrl
     * @param string $gatewayUrl
     * @param string $refundUrl
     */
    public function create(
        string $clientId,
        string $clientSecret,
        string $jwtSecret, // use to validate incoming JWT
        string $accessTokenUrl, // URL to retrieve an access token
        string $paymentRequestUrl, // URL to send a payment request
        string $gatewayUrl,// URL to redirect citizen to
        string $refundUrl // URL to enact refund requests
    ) : Gateway {

        $gateway = Omnipay::create( CustomerPaymentsPlatformGateway::class );
        $gateway->initialize([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'jwtSecret' => $jwtSecret,
            'accessTokenUrl' => $accessTokenUrl,
            'paymentRequestUrl' => $purchaseRequestUrl,
            'gatewayUrl' => $gatewayUrl,
            'refundUrl' => $refundUrl,
        ]);
        return $gateway;
    }

}
