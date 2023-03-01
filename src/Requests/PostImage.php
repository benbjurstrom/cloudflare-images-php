<?php

namespace BenBjurstrom\CloudflareImages\Requests;

use BenBjurstrom\CloudflareImages\Data\ImageData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Contracts\Response;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasMultipartBody;

class PostImage extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $url,
        protected ?string $metadata = null,
        protected ?string $requireSignedURLs = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/v1';
    }

    /**
     * @return array<int, MultipartValue>
     */
    protected function defaultBody(): array
    {
        return [
            new MultipartValue(name: 'url', value: $this->url),
            ...($this->metadata ? [
                new MultipartValue(name: 'metadata', value: $this->metadata),
            ] : []),
            ...($this->requireSignedURLs ? [
                new MultipartValue(name: 'require_signed_urls', value: $this->requireSignedURLs),
            ] : []),
        ];
    }

    public function createDtoFromResponse(Response $response): ImageData
    {
        return ImageData::fromResponse($response);
    }
}
