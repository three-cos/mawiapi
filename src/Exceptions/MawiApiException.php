<?php

namespace Wardenyarn\MawiApi\Exceptions;

class MawiApiException extends \Exception
{
    public function __construct(string $message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct("MawiSoftApi error: " . $message, $code, $previous);
    }
}
