<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Store our default parameters, you can use this class or a subclass of this
 * to set up your parameters
 */
class ParameterStorage
{

    /**
     * @var array
     */
    private static $parameters = [];

    /**
     * Returns the default parameter set used by the CPP gateway class
     * @return array
     */
    public static function getDefault()
    {
        return [
            'clientId' => '',
            'clientSecret' => '',
            'jwtPublicKey' => '',
            'accessTokenUrl' => '',
            'requestPaymentUrl' => '',
            'gatewayUrl' => '',
            'refundUrl' => ''
        ];
    }

    /**
     * Returns the current parameter set
     * @return array
     */
    public static function getAll()
    {
        return self::$parameters;
    }

    /**
     * Pass this method parameters for your environment
     * @param array $parameters
     * @return void
     */
    public static function setAll(array $parameters)
    {
        self::$parameters = array_replace(self::getDefault(), $parameters);
    }

    /**
     * Set a specific parameter
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        self::$parameters[ $key ] = $value;
    }
}
