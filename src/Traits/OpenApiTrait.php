<?php

namespace Wenhsing\Tongtu\Traits;

trait OpenApiTrait
{
    public function dependent(array $data = [])
    {
        if (!$this->config->get('appToken')) {
            $this->config->set('appToken', $this->tt->appToken()->request());
        }
        if (!$this->config->get('merchantId')) {
            $this->config->set('merchantId', $this->tt->getAppBuyerList()->request());
        }
        return [
            'headers' => ['api_version' => '3.0'],
            'query' => [
                'app_token' => $this->config->get('appToken'),
            ],
            'body' => [
                'merchantId' => $this->config->get('merchantId'),
            ],
        ];
    }

    public function format(array $data = [])
    {
        if (isset($data['code']) && 200 == $data['code']) {
            return $data;
        }
        throw new ServerException("Error.", 1, $data);
    }
}
