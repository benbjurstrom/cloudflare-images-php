<?php

namespace BenBjurstrom\CloudflareImages\Data;

use Saloon\Contracts\Response;

final class UploadUrlData
{
    public function __construct(
        public string $id,
        public string $uploadUrl,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json('result');
        if (! is_array($data)) {
            throw new \Exception('Invalid response');
        }

        return new static(
            id: $data['id'],
            uploadUrl: $data['uploadURL'],
        );
    }
}
