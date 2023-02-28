<?php

namespace BenBjurstrom\CloudflareImages\Requests;

use BenBjurstrom\CloudflareImages\Data\ImageData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Contracts\Response;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PatchImage extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, string>|null  $metadata
     */
    public function __construct(
        protected string $id,
        protected ?array $metadata = null,
        protected ?string $requireSignedURLs = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return sprintf('/v1/%s', $this->id);
    }

    /**
     * @return array<string, array<string, string>|string>
     */
    protected function defaultBody(): array
    {
        return [
            ...($this->metadata ? ['metadata' => $this->metadata] : []),
            ...($this->requireSignedURLs ? ['require_signed_urls' => $this->requireSignedURLs] : []),
        ];
    }

    public function createDtoFromResponse(Response $response): ImageData
    {
        return ImageData::fromResponse($response);
    }
}
