<?php

namespace Wenhsing\Tongtu;

class Config
{
    protected $settings = [];

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function get($key, $def = null)
    {
        if ($this->has($key)) {
            return $this->settings[$key];
        }

        return $def;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->settings);
    }

    public function set($key, $value = null)
    {
        $this->settings[$key] = $value;

        return $this;
    }
}
