<?php

namespace Wardenyarn\MawiApi;

trait Invoices
{
    public function getInvoices(array $params = [])
    {
        return $this->getAll('/integration/xml/invoices', 'invoice', $params);
    }

    public function getInvoice(int $id)
    {
        return $this->apiCall('/integration/xml/invoice', ['id' => $id]);
    }

    public function setInvoice(int $clientId, int $userId, int $payerId, int $customerId, array $params = []) 
    {
        $params['clientId'] = $clientId;
        $params['userId'] = $userId;
        $params['payerId'] = $payerId;
        $params['customerId'] = $customerId;
        $params['date'] = isset($params['date']) ? $params['date'] : date('d.m.Y');

        return $this->apiCall('/integration/set/invoice', $params);
    }
}