<?php

namespace Wenhsing\Tongtu;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class Log
{
    protected $config = [
        'name' => 'wenhsing',
        'outpath'  => null,
        'level' => Logger::DEBUG,
    ];

    protected $formatter;
    protected $logger;
    protected $handler;

    protected static $instance;

    public function __construct(array $config = [], LoggerInterface $logger = null)
    {
        if ($logger) {
            $this->setLoggerInstance($logger);
        }
        $this->config = array_merge($this->config, $config);
    }

    public function __call($method, $params)
    {
        call_user_func_array([$this->getLoggerInstance(), $method], $params);
    }

    public static function __callStatic($method, $params)
    {
        forward_static_call_array([self::getInstance(), $method], $params);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::setInstance(new self());
        }
        return self::$instance;
    }

    public static function setInstance(Log $log)
    {
        self::$instance = $log;
    }

    protected function getLoggerInstance()
    {
        if (is_null($this->logger)) {
            $handler = $this->getHandler();
            $handler->setFormatter($this->getFormatter());
            $logger = new Logger($this->config['name']);
            $logger->pushHandler($handler);
            $this->logger = $logger;
        }
        return $this->logger;
    }

    public function setLoggerInstance(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    protected function getHandler()
    {
        if (is_null($this->handler)) {
            $outpath = $this->config['outpath'];
            if (!is_dir($outpath)) {
                mkdir($outpath, 0755, true);
            }
            $file = sprintf("%s/%s.log", $outpath, $this->config['name']);
            $this->setHandler(new StreamHandler($file, $this->config['level']));
        }
        return $this->handler;
    }

    public function setHandler(AbstractHandler $handler)
    {
        $this->handler = $handler;
        return $this;
    }

    public function getFormatter()
    {
        if (is_null($this->formatter)) {
            $this->setFormatter(new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% \n%context% %extra%\n",
                null,
                true,
                true
            ));
        }
        return $this->formatter;

    }

    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }
}
