<?php

namespace Omnipay\NSWGOVCPP;

use Omnipay\Common\Message\AbstractRequest;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractAgencyRequest extends AbstractRequest
{

    /**
      * @param string $url
      * @param array $headers
      * @param array $data
      * @return mixed
      * @throws \Exception|\JsonException
      */
    final protected function doPostRequest(string $url, array $headers = [], array $post_data = [])
    {
        $default_headers = [
            'Accept' => "application/json",
            'Content-Type' => "application/json",
            'User-Agent' => "NSWDPC/CPP-Client (Omnipay)"
        ];
        $headers = array_merge($default_headers, $headers);
        $data = null;
        switch($headers['Content-Type']) {
            case 'application/json':
                $data = json_encode($post_data);
                break;
            case 'application/x-www-form-urlencoded':
                $data = http_build_query($post_data, '', "&", PHP_QUERY_RFC1738);
                break;
            default:
                throw new \Exception("Content-Type must be application/json or application/x-www-form-urlencoded");
                break;
        }

        $response = $this->httpClient->request(
            "POST",
            $url,
            $headers,
            $data
        );
        if ($response instanceof ResponseInterface) {
            $data = json_decode($response->getBody(), true, JSON_THROW_ON_ERROR);
            return $data;
        }
        throw new \Exception("Invalid JSON response from POST request to {$url} error=". json_last_error_msg());
    }

    /**
      * @param string $url
      * @param array $headers
      * @param bool $raw when true, return the response body
      * @return mixed
      * @throws \Exception|\JsonException
      */
    final protected function doGetRequest(string $url, array $headers = [], $raw = false)
    {
        $default_headers = [
            'Accept' => "application/json",
            'User-Agent' => "NSWDPC/CPP-Client (Omnipay)"
        ];
        $headers = array_merge($default_headers, $headers);
        $response = $this->httpClient->request(
            "GET",
            $url,
            $headers
        );
        if ($response instanceof ResponseInterface) {
            if($raw) {
                $output = $response->getBody()->__toString();
            } else {
                $output = json_decode($response->getBody(), true, JSON_THROW_ON_ERROR);
            }
            return $output;
        }
        throw new \Exception("Invalid response from GET request to {$url}");
    }
}
