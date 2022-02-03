<?php

namespace Wardenyarn\MawiApi\Entities;

class Client extends MawiEntity
{
    public function getCustomers()
    {
        return self::$api->getClientCustomers($this->id);
    }

    public function setCustomers(array $params)
    {
        return self::$api->setClientCustomer($this->id, $params);
    }

    public function edit(array $params)
    {
        return self::$api->editClient($this->id, $params);
    }

    public function setCategory($categoryId)
    {
        return self::$api->setClientCategory($this->id, $categoryId);
    }

    public function removeCategory($categoryId)
    {
        return self::$api->removeClientCategory($this->id, $categoryId);
    }
}
