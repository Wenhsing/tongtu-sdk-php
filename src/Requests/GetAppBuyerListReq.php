<?php

namespace Wenhsing\Tongtu\Requests;

use Wenhsing\Tongtu\Traits\ApiSignTrait;

class GetAppBuyerListReq extends Request
{
    use ApiSignTrait;

    protected $method = 'GET';
    protected $uri = '/open-platform-service/partnerOpenInfo/getAppBuyerList';

    public function dependent(array $data = null)
    {
        if (!$this->config->get('appToken')) {
            $this->config->set('appToken', $this->tt->appToken()->request());
        }
        return [
            'query' => [
                'app_token' => $this->config->get('appToken'),
            ],
        ];
    }

    public function format(array $data = [])
    {
        if (
            isset($data['success'])
            && isset($data['code'])
            && $data['success']
            && 0 == $data['code']
        ) {
            return $data['datas'][0]['partnerOpenId'] ?? '' ;
        }
        throw new ServerException("Error.", 1, $data);
    }
}
