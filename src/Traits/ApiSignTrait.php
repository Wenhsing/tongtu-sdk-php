<?php

namespace Wenhsing\Tongtu\Traits;

trait ApiSignTrait
{
    public function sign(array $params)
    {
        unset($params['sign']);
        $params['timestamp'] = time();
        $str = '';
        foreach ($params as $k => $v) {
            $str .= $k.$v;
        }
        $params['sign'] = md5($str . $this->config->get('app_secret', ''));
        return $params;
    }
}
