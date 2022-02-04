<?php

namespace Wardenyarn\MawiApi;

use GuzzleHttp\Exception\ServerException;
use Wardenyarn\MawiApi\Entities\Client;

trait Clients
{
    public function getClients(array $params = [])
    {
        return $this->getAll('/integration/admin/xml/clients', 'client', $params, Client::class);
    }

    public function getClient(int $id)
    {
        return $this->apiCall('/integration/xml/client', ['id' => $id], Client::class);
    }

    public function setClient(array $params)
    {
        return $this->apiCall('/integration/set/client', $params);
    }

    public function editClient(int $clientId, array $params)
    {
        $params['id'] = $clientId;

        return $this->setClient($params);
    }

    public function setClientCategory(int $clientId, int $categoryId)
    {
        try {
            $this->apiCall('/integration/set/clientCategory', [
                'id' => $clientId,
                'categoryId' => $categoryId,
                'mode' => 'true',
            ]);
        } catch (ServerException $e) {
            return false;
        }

        return true;
    }

    public function removeClientCategory(int $clientId, int $categoryId)
    {
        $this->apiCall('/integration/set/clientCategory', [
            'id' => $clientId,
            'categoryId' => $categoryId,
            'mode' => 'false',
        ]);

        /* Warning! This API call always returns true even if no category was removed */
        return true;
    }

    public function getClientCustomers(int $clientId)
    {
        return $this->getArrayResult(
            $this->apiCall('/integration/xml/customers', ['clientId' => $clientId])->customer
        );
    }

    public function setClientCustomer(int $clientId, array $params)
    {
        $params['clientId'] = $clientId;

        return $this->apiCall('/integration/set/customer', $params);
    }
}