<?php

namespace Wenhsing\Tongtu\Requests;

use Wenhsing\Tongtu\Contracts\Request as RequestContract;
use Wenhsing\Tongtu\Event;
use Wenhsing\Tongtu\Events\BeforeRequestEvent;
use Wenhsing\Tongtu\Events\RequestErrorEvent;
use Wenhsing\Tongtu\Exceptions\ClientException;
use Wenhsing\Tongtu\Exceptions\ServerException;
use Wenhsing\Tongtu\Tongtu;

class Request implements RequestContract
{
    protected $config;
    protected $tt;

    protected $method = 'POST';
    protected $uri;
    protected $httpClient;

    protected $appToken;
    protected $merchantId;

    public function __construct(Tongtu $tt)
    {
        $this->tt = $tt;
        $this->config = $tt->getConfig();
    }

    public function dependent(array $data = [])
    {
    }

    public function request(array $data = [])
    {
        if (empty($this->uri)) {
            throw new ClientException('[uri] does not exist.');
        }

        $reqData = [];
        $dependent = $this->dependent($data);
        if (isset($dependent) && is_array($dependent)) {
            $reqData = $dependent;
        }
        $reqData = array_replace_recursive($reqData, $data);
        if (method_exists($this, 'sign')) {
            $reqData['query'] = $this->sign($reqData['query'] ?? []);
        }
        if (isset($reqData['body']) && is_array($reqData['body'])) {
            $reqData['body'] = json_encode($reqData['body'] ?? []);
        }

        try {
            Event::dispatch(new BeforeRequestEvent($reqData));
            $res = $this->http()->request($this->method, $this->uri, $reqData);

            return $this->parseResponse($res);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->hasResponse()
                ? $response = (string) $e->getResponse()->getBody()
                : $response = $e->getMessage();
            Event::dispatch(new RequestErrorEvent($e));
            throw new ServerException('Tongtool Request Error.', 1, $response);
        }
    }

    public function parseResponse(\GuzzleHttp\Psr7\Response $response)
    {
        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new ServerException('Formatted json failed.', 1, $body);
        }
        if (method_exists($this, 'format')) {
            $tmp = $this->format($data);
            if (isset($tmp)) {
                $data = $tmp;
            }
        }

        return $data;
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
