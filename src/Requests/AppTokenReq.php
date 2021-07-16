<?php

namespace Wenhsing\Tongtu\Requests;

class AppTokenReq extends Request
{
    protected $method = 'GET';
    protected $uri = '/open-platform-service/devApp/appToken';

    public function dependent(array $data = null)
    {
        return [
            'query' => [
                'accessKey' => $this->config->get('app_key', ''),
                'secretAccessKey' => $this->config->get('app_secret', ''),
            ],
        ];
    }
}
