<?php

namespace Wardenyarn\MawiApi;

trait Payments
{
    public function getPayments(array $params = [])
    {
        $this->getAll('/integration/xml/payments', 'payment', $params);
    }

    public function getPayment(int $id)
    {
        return $this->apiCall('/integration/xml/payment', ['id' => $id]);
    }

    public function getPaymentPrint(int $id)
    {
        return $this->apiCall('/integration/xml/paymentPrint.jsp', ['id' => $id]);
    }
}