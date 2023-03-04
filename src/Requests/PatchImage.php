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

    public function __construct(
        protected string $id
    ) {
    }

    public function resolveEndpoint(): string
    {
        return sprintf('/v1/%s', $this->id);
    }

    public function createDtoFromResponse(Response $response): ImageData
    {
        return ImageData::fromResponse($response);
    }
}
