<?php

namespace Wardenyarn\MawiApi;

trait People
{
    public function getPeople(array $params = [])
    {
        return $this->getAll('/integration/xml/people', 'person', $params);
    }

    public function setPerson(int $clientId, array $params = [])
    {
        $params['clientId'] = $clientId;

        return $this->apiCall('/integration/set/person', $params);
    }

    public function editPerson(int $id, array $params = [])
    {
        $params['id'] = $id;

        return $this->apiCall('/integration/set/person', $params);
    }

    public function getSeller(int $id)
    {
        return $this->apiCall('/integration/xml/seller', ['id' => $id]);
    }

    public function getUsers($department = '-1')
    {
        return $this->getAll('/integration/xml/users', 'user', ['departmentId' => $department]);
    }
}