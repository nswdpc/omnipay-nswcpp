<?php

namespace Omnipay\NSWGOVCPP;

use Firebase\JWT\JWT;
use Omnipay\NSWGOVCPP\CompletePurchaseRequestException;
use Omnipay\NSWGOVCPP\UnprocessableEntityException;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle payment completion requests from the CPP
 *
 * The CPP will send a JWT which can be used to determine if the request was valid
 * At this point, no other validations are made. Your application should verify the JWT and handle the payment data
 * @author James
 */
class CompletePurchaseRequest extends AbstractAgencyRequest
{

    use GetterSetterParameterTrait;
    use NeedsJWTDecodeTrait;

    protected $exitOnError = true;

    /**
     * Set whether to exit or not on JWT decode errors
     * The default is true but this can be turned off for testing purposes
     */
    public function setExitOnError(bool $exit) {
        $this->exitOnError = $exit;
        return $this;
    }

    /**
     * Return exitOnError value
     */
    public function getExitOnError() : bool {
        return $this->exitOnError;
    }

    /**
     * Validate and returns the JWT payload
     * @return array
     * @throws JWTDecodeException
     */
     public function getData() : array {
        $this->validateJWT();
        return $this->jwtPayload;
     }

     /**
      * Catch any errors in the send process in order to return the correct
      * HTTP response code immediately without further processing
      *
      * @return ResponseInterface
      */
     public function send()
     {
        try {
            // send the request, decode JWT, use payload to complete payment
            return parent::send();
        } catch (JWTDecodeException $e) {
            // Specific JWT decode error
            // This is generally a 50x error code
            $code = $e->getCode();
        } catch (UnprocessableEntityException $e) {
            // this exception will always trigger a 422
            $code = Response::HTTP_UNPROCESSABLE_ENTITY;
        } catch (\Exception $e) {
            // default HTTP code for errors is 503
            $code = Response::HTTP_SERVICE_UNAVAILABLE;
        }

        // Error condition handling
        // sanity check on the HTTP error code
        $code = intval($code);
        if($code < 400 || $code > 599) {
            // ensure we use a sane 50x error code if the code provided
            // would tell the CPP incorrect information
            $code = Response::HTTP_SERVICE_UNAVAILABLE;
        }

        // create the response
        $response = new Response(
            'An error occurred',
            $code,
            [
                'content-type' => 'text/html'
            ]
        );
        $response->send();

        if($this->getExitOnError()) {
            // exit to avoid modules interfering with this response
            exit;
        } else {
            return $response;
        }
     }

     /**
      * Create a CompletePurchaseResponse instance to represent payment completion
      * @param array $data
      * @throws JWTDecodeException
      */
    public function sendData($data) : ResponseInterface {

        // check that there is a payload
        if(empty($data)) {
            throw new JWTDecodeException("The decoded JWT payload was empty");
        }

        // create the complete purchase response, provide it the JWT payload
        $response = new CompletePurchaseResponse($this, $data);
        return $response;
    }
}
