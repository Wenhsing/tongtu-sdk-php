<?php

namespace Wenhsing\Tongtu\Contracts;

use Wenhsing\Tongtu\Tongtu;

interface Request
{
    public function __construct(Tongtu $tt);

    public function dependent();
}
