<?php

namespace Wardenyarn\MawiApi;

trait Events
{
    public function getEvents(array $params = [])
    {
        return $this->getAll('/integration/xml/events', 'object', $params);
    }

    public function setEvent(int $eventTypeId, int $clientId, int $userId, array $params = [])
    {
        $params['ownerId'] = $clientId;
        $params['userId'] = $userId;
        $params['eventTypeId'] = $eventTypeId;
        $params['ownerName'] = 'client';

        return $this->apiCall('/integration/set/event', $params);
    }

    public function setReport(int $eventId, $message, bool $success = true)
    {
        $success_value = $success ? 'on' : 'off';

        return $this->apiCall('/integration/set/report', [
            'report.id' => $eventId,
            'report.message' => $message,
            'report.success' => $success_value,
        ]);
    }
}