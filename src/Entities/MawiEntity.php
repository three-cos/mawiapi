<?php

namespace Wardenyarn\MawiApi\Entities;

use Wardenyarn\MawiApi\Exceptions\MawiApiException;

class MawiEntity
{
    static protected $api = null;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $this->convert($value);
        }
    }

    public function convert($data)
    {
        if (isset($data['@attributes'])) {
            $data = new self($data);
        }

        if (is_array($data)) {
            foreach ($data as &$d) {
                $d = $this->convert($d);
            }
        }

        return $data;
    }

    public function __get($attr)
    {
        $attr[0] = strtolower($attr[0]);
        $attr = preg_replace('/([A-Z])/', '-$1', $attr);
        $attr = strtolower($attr);
        
        if (isset($this->$attr)) {
            return $this->$attr;
        }

        if (isset($this->{'@attributes'}[$attr])) {
            return $this->{'@attributes'}[$attr];
        }

        throw new MawiApiException(sprintf('No such "%s" attribute', $attr));
    }

    static public function setApi($api)
    {
        self::$api = $api;
    }
}
