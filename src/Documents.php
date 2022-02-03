<?php

namespace Wardenyarn\MawiApi;

use Wardenyarn\MawiApi\Entities\MawiEntity;
use Wardenyarn\MawiApi\Exceptions\MawiApiException;

trait Documents
{
    public function getDocuments($params = [])
    {
        return $this->getAll('/integration/xml/documents', 'document', $params);
    }

    public function getDocument(int $id)
    {
        return $this->apiCall('/integration/xml/document', ['id' => $id]);
    }

    public function downloadDocument(int $id, $file_dir)
    {
        $file_dir = rtrim($file_dir, '/');

        if (! is_writable($file_dir)) {
            throw new MawiApiException(sprintf('Document download directory "%s" is not writable', $file_dir));
        }

        $document = $this->getDocument($id);

        $remote_file = urldecode($document->href);

        $filename = basename(explode('?id=', $remote_file)[0]);

        $local_file = fopen($file_dir.'/'.$filename, 'w');

        $body = $this->http->get($remote_file, ['sink' => $local_file]);

        fclose($local_file);

        return $body->getStatusCode() === 200;
    }
}