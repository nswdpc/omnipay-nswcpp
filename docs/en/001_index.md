# Documentation

> Prequisites: you have an active agency account with the CPP, including tests endpoints, a client id and secret.

## Getting an access token and starting the payment process

[Access and authorise payment request](./002_access_and_authorise_payment_request.md)

## Completing a payment

[Completing a payment](./003_payment_completion.md)

## Refund a payment

[Refund a payment](./004_refund_payment.md)


## Gateway factory

As a shortcut, you can create a NSWGOV CPP factory gateway quickly using your configuration values.

```php
use NSWDPC\Payments\CPP\GatewayFactory;

use Omnipay\Omnipay;

/**
 * @var NSWDPC\Payments\CPP\Gateway
 */
$gateway = GatewayFactory::create(
    $clientId,
    $clientSecret,
    $jwtSecret,
    $accessTokenUrl,
    $paymentRequestUrl,
    $gatewayUrl,
    $refundUrl
);
```