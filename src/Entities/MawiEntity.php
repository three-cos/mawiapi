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
        $attr = $this->toKebabCase($attr);
        
        if (isset($this->$attr)) {
            return $this->$attr;
        }

        if (isset($this->{'@attributes'}[$attr])) {
            return $this->{'@attributes'}[$attr];
        }

        throw new MawiApiException(sprintf('No such "%s" attribute', $attr));
    }

    protected function toKebabCase(string $string)
    {
        $string[0] = strtolower($string[0]);
        $string = preg_replace('/([A-Z])/', '-$1', $string);
        $kebab_string = strtolower($string);
        
        return $kebab_string;
    }

    static public function setApi($api)
    {
        self::$api = $api;
    }
}
