<?php

namespace Wardenyarn\MawiApi\Entities;

class Proposal extends MawiEntity
{
    const VAT_0  = 1;
    const VAT_10 = 2;
    const VAT_18 = 3;
    const VAT_20 = 4;

    public function getClient()
    {
        return self::$api->getClient($this->client->id);
    }

    public function getEvents()
    {
        $client = $this->getClient();

        return array_reduce($client->event, function ($events, $event) {
            try {
                if ($event->proposalId == $this->id) {
                    $events[] = $event;
                }
            } catch (\Throwable $th) {}

            return $events;
        }, []);
    }
}
