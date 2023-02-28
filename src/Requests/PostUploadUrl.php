<?php

namespace BenBjurstrom\CloudflareImages\Requests;

use BenBjurstrom\CloudflareImages\Data\UploadUrlData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Contracts\Response;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasMultipartBody;

class PostUploadUrl extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $metadata = null,
        protected ?string $requireSignedURLs = null,
        protected ?string $id = null,
        protected ?string $expiry = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/direct_upload';
    }

    public function createDtoFromResponse(Response $response): UploadUrlData
    {
        return UploadUrlData::fromResponse($response);
    }

    /**
     * @return array<int, MultipartValue>
     */
    protected function defaultBody(): array
    {
        return [
            ...($this->metadata ? [
                new MultipartValue(name: 'metadata', value: $this->metadata),
            ] : []),
            ...($this->requireSignedURLs ? [
                new MultipartValue(name: 'require_signed_urls', value: $this->requireSignedURLs),
            ] : []),
            ...($this->id ? [
                new MultipartValue(name: 'id', value: $this->id),
            ] : []),
            ...($this->expiry ? [
                new MultipartValue(name: 'expiry', value: $this->expiry),
            ] : []),
        ];
    }
}
