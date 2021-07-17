<?php

namespace Wenhsing\Tongtu;

use Exception;
use Wenhsing\Tongtu\Contracts\Request as RequestContract;
use Wenhsing\Tongtu\Exceptions\ClientException;
use Wenhsing\Tongtu\Listeners\LogEventSubscriber;

class Tongtu
{
    public const VERSION = '1.0.0';

    protected $config;

    private $finder;

    public function __construct(Config $config)
    {
        $this->setConfig($config);
        $this->registerEventService();
        $this->registerLogService();
    }

    public function __call($method, $params = [])
    {
        return $this->action($method, ...$params);
    }

    protected function action(string $action, array $params = [])
    {
        $this->checkEnable();
        $method = $this->getActionName($action);
        if (class_exists($method)) {
            $concrete =  new $method($this);
            if ($concrete instanceof RequestContract) {
                return $concrete;
            }
            throw new ClientException("[$action] must be an instance of ".RequestContract::class);
        }
        throw new ClientException("Action [$action] does not exists");
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    protected function getActionName($method)
    {
        if (!$this->finder) {
            $this->finder = function ($method) {
                $method = ucwords(str_replace(['-', '_'], ' ', $method));
                $method = str_replace(' ', '', $method);
                return __NAMESPACE__.'\\Requests\\'.$method.'Req';
            };
        }
        $cb = $this->finder;
        return $cb($method);
    }

    public function setActionNameFinder(callable $cb)
    {
        $this->finder = $cb;
        return $this;
    }

    protected function registerEventService()
    {
        Event::addSubscriber(new LogEventSubscriber());
    }

    protected function registerLogService()
    {
        Log::setInstance(new Log($this->config->get('log', [])));
    }

    protected function checkEnable()
    {
        if (false == $this->config->get('enable', false)) {
            throw new ClientException('Application closed');
        }
    }
}
