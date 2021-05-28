<?php

namespace Omnipay\NSWGOVCPP;

use Symfony\Component\HttpFoundation\Response;

/**
 * Method not allowed exception
 * @author James
 */
class NotAllowedException extends \Exception
{
    protected $code = Response::HTTP_METHOD_NOT_ALLOWED;
}
