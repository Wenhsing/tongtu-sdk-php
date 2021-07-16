<?php

namespace Wenhsing\Tongtu\Events;

class AfterRequestEvent
{
    public $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
}
