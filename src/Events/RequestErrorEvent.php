<?php

namespace Wenhsing\Tongtu\Events;

use GuzzleHttp\Exception\RequestException;

class RequestErrorEvent
{
    public $headers;

    public $uri;

    public $body;

    public $response;

    public function __construct(RequestException $data)
    {
        $headers = [];
        foreach ($data->getRequest()->getHeaders() as $k => $v) {
            $headers[$k] = implode(',', $v);
        }
        $this->headers = $headers;
        $this->uri = (string) $data->getRequest()->getUri();
        $this->body = (string) $data->getRequest()->getBody();
        $response = $data->hasResponse() ? $data->getResponse()->getBody() : $data->getMessage();
        $this->response = (string) $response;
    }
}
