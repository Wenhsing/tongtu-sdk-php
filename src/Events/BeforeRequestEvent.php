<?php

namespace Wenhsing\Tongtu\Events;

class BeforeRequestEvent
{
    public $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
}
