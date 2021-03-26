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
        $response = $this->httpClient->request(
            "POST",
            $url,
            $headers,
            json_encode($post_data)
        );
        if ($response instanceof ResponseInterface) {
            $data = json_decode($response->getBody(), true, JSON_THROW_ON_ERROR);
            return $data;
        }
        throw new \Exception("Invalid response from POST request to {$url}");
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
