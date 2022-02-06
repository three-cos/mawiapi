<?php

namespace Wardenyarn\MawiApi\Entities;

use Wardenyarn\MawiApi\Exceptions\MawiApiException;

class MawiEntity
{
    static protected $api = null;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $this->convert($key, $value);
        }
    }

    public function convert($key, $data)
    {
        if (isset($data['@attributes'])) {
            $data = $this->newEntity($key, $data);
        }

        if (is_array($data)) {
            foreach ($data as $k => &$d) {
                $d = $this->convert($k, $d);
            }
        }

        return $data;
    }

    protected function newEntity($type, $data)
    {
        switch ($type) {
            case 'client':
                $class = Client::class;
                break;
            
            case 'product':
                $class = Product::class;
                break;
            
            case 'event':
                $class = Event::class;
                break;
            
            case 'proposal':
                $class = Proposal::class;
                break;
        
            default:
                $class = self::class;
                break;
        }

        return new $class($data);
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
