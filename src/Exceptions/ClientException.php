<?php

namespace Wenhsing\Tongtu\Exceptions;

use RuntimeException;

class ClientException extends RuntimeException
{
    protected $data;

    public function __construct($message, $code = 0, $data = null, \Throwable $previous = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
        parent::__construct($this->message, $this->code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }
}
