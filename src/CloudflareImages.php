<?php

namespace BenBjurstrom\CloudflareImages;

use Saloon\Http\Connector;

class CloudflareImages extends Connector
{
    public function __construct(
        public string $apiToken,
        public string $accountId,
    ) {
        $this->withTokenAuth($this->apiToken);
    }

    public function resolveBaseUrl(): string
    {
        return sprintf(
            'https://api.cloudflare.com/client/v4/accounts/%s/images',
            $this->accountId
        );
    }
}
