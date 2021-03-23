<?php

namespace Omnipay\NSWGOVCPP;

/**
 * Specific exception thrown when the incoming JWT cannot be decoded
 * This results in a 422 exception
 * @author James
 */
class JWTDecodeException extends UnprocessableEntityException {}
