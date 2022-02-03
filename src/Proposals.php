<?php

namespace Wardenyarn\MawiApi;

use Wardenyarn\MawiApi\Entities\Proposal;

trait Proposals
{
    public function getProposals(array $params = [])
    {
        return $this->getAll('/integration/xml/proposals', 'proposal', $params, Proposal::class);
    }

    public function getProposal(int $id)
    {
        return $this->apiCall('/integration/xml/proposal', ['id' => $id], Proposal::class);
    }

    public function getProposalItems(array $params = [])
    {
        return $this->getArrayResult(
            $this->apiCall('/integration/xml/proposalsItems', $params)->item
        );
    }

    public function setProposal(int $clientId, int $userId, int $productId, int $vatRateId, array $params = [])
    {
        $params['clientId'] = $clientId;
        $params['userId'] = $userId;
        $params['productId'] = $productId;
        $params['vatRateId'] = $vatRateId;
        $params['date'] = $params['date'] ? $params['date'] : date('d.m.Y');

        return $this->apiCall('/integration/set/proposal', $params);
    }

    public function editProposal(int $proposalId, int $userId, int $productId, int $vatRateId, array $params = [])
    {
        $params['id'] = $proposalId;
        $params['userId'] = $userId;
        $params['productId'] = $productId;
        $params['vatRateId'] = $vatRateId;

        return $this->apiCall('/integration/set/proposal', $params);
    }
}