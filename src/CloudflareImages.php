<?php

namespace BenBjurstrom\CloudflareImages;

use Exception;
use Saloon\Http\Connector;

class CloudflareImages extends Connector
{
    public function __construct(
        public string $apiToken,
        public string $accountId,
        public ?string $signingKey = null,
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

    public function images(): ImageResource
    {
        return new ImageResource($this);
    }

    public function variants(): VariantResource
    {
        return new VariantResource($this);
    }

    public function signUrl(string $url): string
    {
        if ($this->signingKey === null) {
            throw new Exception('Signing key is not set');
        }

        $urlPath = parse_url($url, PHP_URL_PATH);
        if (! $urlPath) {
            throw new Exception('Unable to parse URL');
        }

        $sig = hash_hmac('sha256', $urlPath, $this->signingKey);

        return $url.'?sig='.$sig;
    }
}
