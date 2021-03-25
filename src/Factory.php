<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\Omnipay;

/**
 * Factory class used to create a Gateway instance
 * TBD if this is needed
 *
 * @author James
 */
class Factory
{

    /**
     * Create a new instance of {@link Omnipay\NSWGOVCPP\Gateway} and return it
     * @param ParameterStorage $parameterStorage
     */
    public function create(ParameterStorage $parameterStorage) : Gateway
    {
        $gateway = Omnipay::create(CustomerPaymentsPlatformGateway::class);
        $gateway->initialize($parameterStorage::getAll());
        return $gateway;
    }
}
