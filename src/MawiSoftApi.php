<?php

namespace Wardenyarn\MawiApi;

use GuzzleHttp\Client;
use Mtownsend\XmlToArray\XmlToArray;
use Wardenyarn\MawiApi\Entities\MawiEntity;
use Wardenyarn\MawiApi\Exceptions\MawiApiException;

class MawiSoftApi
{
    use Clients, Documents, Invoices, Payments, People, Products, Proposals, Events;

    protected $currentPage = 0;
    protected $pageSize = 20;
    protected $limit = 20;
    
    public function __construct($host, $login, $password)
    {
        $this->http = new Client([
            'base_uri' => "http://{$host}.mawisoft.ru",
            'cookies' => true, 
            'auth' => [$login, $password],
        ]);

        $this->authenticate();

        MawiEntity::setApi($this);
    }

    public function authenticate()
    {
        $this->http->get('/integration/init');
    }

    public function getAll($path, $entity, $params = [], $class = null)
    {
        $items = 0;
        $nextPageAvailable = true;
        $this->currentPage = 0;

        while (true) {
            if (! $nextPageAvailable || $this->limit == $items) {
                break;
            }
            
            $body = $this->http->get($path, [
                'query' => array_merge($params, [
                    'pageSize' => $this->pageSize,
                    'page' => $this->currentPage++,
                ])
            ]);

            $arrayResult = $this->getApiResult($body);

            if (! isset($arrayResult[$entity])) {
                break;
            }

            if (isset($arrayResult['page-result'])) {
                $nextPageAvailable = $arrayResult['page-result']['@attributes']['page-count'] > $this->currentPage;
            }

            $class = ($class) ? $class : MawiEntity::class;

            if (! isset($arrayResult[$entity][0])) {
                yield new $class($arrayResult[$entity]);
            } else {
                foreach ($arrayResult[$entity] as $item) {
                    if ($this->limit < ++$items) {
                        break(2);
                    }
    
                    yield new $class($item);
                }
            }
        }
    }

    public function apiCall(string $path, array $params = [], $class = null)
    {
        $body = $this->http->get($path, [
            'query' => $params
        ]);

        $data = $this->getApiResult($body);

        if (is_numeric($data) || is_bool($data)) {
            return $data;
        }

        $class = ($class) ? $class : MawiEntity::class;

        return new $class($data);
    }

    public function parseXMLResult($xml)
    {   
        $result = XmlToArray::Convert($xml);

        return $result;
    }

    public function getApiResult($body)
    {
        $result = $body->getBody()->getContents();

        if (is_numeric($result)) {
            return (int) $result;
        }

        if (strpos($result, 'xml') !== false ) {
            return $this->parseXMLResult($result);
        }

        return (bool) $result;
    }

    public function getArrayResult($items)
    {
        if (is_a($items, MawiEntity::class)) {
            return array($items);
        }
        
        return $items;
    }

    public function setLimit(int $limit)
    {
        if ($limit < 1) {
            throw new MawiApiException("Limit must be greater than 0");
        }

        $this->limit = $limit;

        return $this;
    }

    public function setPageSize(int $size)
    {
        if ($size < 1 || $size > 500) {
            throw new MawiApiException("Page size must be a number between 1 and 500");
        }

        $this->pageSize = $size;

        return $this;
    }
}
