# Documentation

> Prequisites: you have an active agency account with the CPP, including tests endpoints, a client id and secret.

+ [Make a payment](./002_access_and_authorise_payment_request.md)
+ [Completing a payment](./003_payment_completion.md)
+ [Refunding a payment](./004_refund_payment.md)

Documentation improvements are welcome, open PR to do so.

## Gateway factory

As a shortcut, you can create a `Omnipay\NSWGOVCPP\Gateway` instance quickly using your configuration values and the `Factory` class:

```php
use Omnipay\NSWGOVCPP\Factory;
use Omnipay\NSWGOVCPP\ParameterStorage;

$config = [
    'clientId' => 'your-client-id',
    'clientSecret' => 'your-client-secret',
    'jwtPublicKey' => 'your jwt public key',
    'accessTokenUrl' => 'https://auth.example.com/accesstoken',
    'requestPaymentUrl' => 'https://payments.example.com/paymentrequest',
    'gatewayUrl' => 'https://gateway.example.com/',
    'refundUrl' => 'https://payments.example.com/refund'
];
$parameters = ParameterStorage::setAll($config);
/**
 * @var Omnipay\NSWGOVCPP\Gateway
 */
$gateway = Factory::create( $parameters );
```
