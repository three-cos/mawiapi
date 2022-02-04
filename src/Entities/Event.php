<?php

namespace Wardenyarn\MawiApi\Entities;

class Event extends MawiEntity
{
    const TYPE_ACTION  = 1000;
    const TYPE_OTHER   = 1001;
    const TYPE_MEETING = 1002;
    const TYPE_EMAIL   = 1003;
    const TYPE_CALL    = 1004;

    public function setReport($message, bool $success = true)
    {
        self::$api->setReport($this->id, $message, $success);
    }
}
