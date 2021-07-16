<?php

namespace Wenhsing\Tongtu\Requests;

use Wenhsing\Tongtu\Contracts\Request as RequestContract;
use Wenhsing\Tongtu\Event;
use Wenhsing\Tongtu\Events\BeforeRequestEvent;
use Wenhsing\Tongtu\Exceptions\ClientException;
use Wenhsing\Tongtu\Tongtu;

class Request implements RequestContract
{
    protected $config;
    protected $tt;

    protected $method = 'POST';
    protected $uri;
    protected $httpClient;

    public function __construct(Tongtu $tt)
    {
        $this->tt = $tt;
        $this->config = $tt->getConfig();
    }

    public function dependent(array $data = null)
    {
    }

    public function request(array $data = null)
    {
        if (empty($this->uri)) {
            throw new ClientException('[uri] does not exist.');
        }

        $reqData = [];
        $dependent = $this->dependent($data);
        if (isset($dependent) && is_array($dependent)) {
            $reqData = $dependent;
        }
        if ($data) {
            $reqData = array_replace_recursive($reqData, $data);
        }

        try {
            Event::dispatch(new BeforeRequestEvent($reqData));
            $res = $this->http()->request($this->method, $this->uri, $reqData);

            return $res;
            // return $this->parseResponse($res);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // if ($e->hasResponse()) {
            //     $response = (string) $e->getResponse()->getBody();
            // } else {
            //     $response = $e->getMessage();
            // }
            // Event::dispatch(new RequestErrorEvent($e));
            // throw new ErpException("Tongtool Request Error.", 1, $response);
        }
    }

    protected function http()
    {
        if (!$this->httpClient) {
            $os = \PHP_OS;
            $osVersion = php_uname('r');
            $osMode = php_uname('m');
            $userAgent = hex2bin('57656e6873696e67')." ($os $osVersion; $osMode) ";
                $this->httpClient = new \GuzzleHttp\Client([
                'base_uri' => 'https://open.tongtool.com',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'User-Agent' => $userAgent,
                ],
            ]);
        }
        return $this->httpClient;
    }
}
